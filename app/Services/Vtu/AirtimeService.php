<?php

namespace App\Services\Vtu;

use App\Models\AirtimeDiscount;
use App\Models\Network;
use App\Models\PlanAirtimeDiscount;
use App\Models\Store;
use App\Models\StoreAirtimeDiscount;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ReferralService;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AirtimeService
{
    public function __construct(
        protected WalletService $walletService,
        protected VtuLabService $vtuLabService
    ) {}

    /**
     * Resolve effective pricing, limits, and profit margins for an airtime recharge.
     */
    public function resolvePricing(Store $store, Network $network, float $faceAmount): array
    {
        // 1. Master global configuration
        $global = AirtimeDiscount::where('network_id', $network->id)->first();
        $globalActive = $global ? (bool) $global->is_active : true;
        $globalRetailDiscount = $global ? (float) $global->default_retail_discount : 1.50;
        $globalMerchantDiscount = $global ? (float) $global->default_merchant_discount : 2.00;
        $globalMin = $global ? (float) $global->min_amount : 50.00;
        $globalMax = $global ? (float) $global->max_amount : 50000.00;

        // 2. Store specific customer overrides
        $storeDiscount = StoreAirtimeDiscount::where('store_id', $store->id)
            ->where('network_id', $network->id)
            ->first();

        $isStoreEnabled = $storeDiscount ? (bool) $storeDiscount->is_enabled : $globalActive;
        $effectiveRetailDiscount = ($storeDiscount && $storeDiscount->selling_discount !== null)
            ? (float) $storeDiscount->selling_discount
            : $globalRetailDiscount;

        // 3. Reseller / Tenant wholesale tier pricing
        $effectiveWholesaleDiscount = $globalMerchantDiscount;
        $tierMin = null;
        $tierMax = null;

        $subscription = Subscription::where('store_id', $store->id)
            ->whereIn('status', ['active', 'trialing'])
            ->latest()
            ->first();

        if ($subscription && $subscription->plan_id) {
            $tierPrice = PlanAirtimeDiscount::where('plan_id', $subscription->plan_id)
                ->where('network_id', $network->id)
                ->first();

            if ($tierPrice && $tierPrice->wholesale_discount !== null) {
                $effectiveWholesaleDiscount = (float) $tierPrice->wholesale_discount;
                $tierMin = $tierPrice->min_amount !== null ? (float) $tierPrice->min_amount : null;
                $tierMax = $tierPrice->max_amount !== null ? (float) $tierPrice->max_amount : null;
            }
        }

        // 4. Effective Min and Max Limits
        $effectiveMin = $storeDiscount?->min_amount !== null
            ? (float) $storeDiscount->min_amount
            : ($tierMin ?? $globalMin);

        $effectiveMax = $storeDiscount?->max_amount !== null
            ? (float) $storeDiscount->max_amount
            : ($tierMax ?? $globalMax);

        // 5. Calculations
        $customerRetailPrice = round($faceAmount * (1 - ($effectiveRetailDiscount / 100)), 2);
        $storeWholesaleCost = round($faceAmount * (1 - ($effectiveWholesaleDiscount / 100)), 2);
        $profitMargin = max(0, round($customerRetailPrice - $storeWholesaleCost, 2));

        return [
            'is_enabled' => $isStoreEnabled && $network->is_active,
            'face_amount' => $faceAmount,
            'retail_discount' => $effectiveRetailDiscount,
            'wholesale_discount' => $effectiveWholesaleDiscount,
            'customer_retail_price' => $customerRetailPrice,
            'store_wholesale_cost' => $storeWholesaleCost,
            'profit_margin' => $profitMargin,
            'min_amount' => $effectiveMin,
            'max_amount' => $effectiveMax,
        ];
    }

    /**
     * Execute an atomic Airtime recharge for a customer.
     */
    public function buyAirtime(User $customer, Network $network, float $faceAmount, string $phoneNumber): array
    {
        // 1. Normalize phone number
        $phone = preg_replace('/\D+/', '', $phoneNumber);
        if (strlen($phone) !== 11) {
            throw new \InvalidArgumentException('Please enter a valid 11-digit phone number (e.g. 08012345678).');
        }

        if (! $network->is_active) {
            throw new \Exception("{$network->name} network is currently unavailable.");
        }

        // 2. Resolve store context
        $store = (method_exists($customer, 'store') ? $customer->store : null)
            ?? Store::where('owner_id', $customer->id)->first()
            ?? Store::first();

        if (! $store) {
            throw new \Exception('Store context is missing for airtime purchase.');
        }

        // 3. Resolve pricing & limits
        $pricing = $this->resolvePricing($store, $network, $faceAmount);

        if (! $pricing['is_enabled']) {
            throw new \Exception("Airtime top-up for {$network->name} is currently disabled on this store.");
        }

        if ($faceAmount < $pricing['min_amount']) {
            throw new \InvalidArgumentException("Minimum recharge amount for {$network->name} is ₦".number_format($pricing['min_amount'], 2));
        }

        if ($faceAmount > $pricing['max_amount']) {
            throw new \InvalidArgumentException("Maximum recharge amount for {$network->name} is ₦".number_format($pricing['max_amount'], 2));
        }

        $customerRetailPrice = $pricing['customer_retail_price'];
        $storeWholesaleCost = $pricing['store_wholesale_cost'];
        $profitMargin = $pricing['profit_margin'];

        // 4. Check Wallet Balances
        $customerWallet = $customer->wallet('main');
        if (! $customerWallet || (float) $customerWallet->balance < $customerRetailPrice) {
            throw new \Exception('Insufficient wallet balance. Please fund your account to complete this recharge.');
        }

        $storeMainWallet = $store->mainWallet();
        if ((float) $storeMainWallet->balance < $storeWholesaleCost) {
            throw new \Exception('Store wholesale balance low. Please contact store support.');
        }

        // 5. Generate Reference
        $storePrefix = strtoupper(Str::slug($store->name ?? 'AFF', '_'));
        $txReference = $storePrefix.'_AIRTIME_'.date('YmdHis').'_'.strtoupper(Str::random(6));

        DB::beginTransaction();
        try {
            // 1. Debit Customer Wallet
            $this->walletService->debit(
                $customerWallet,
                $customerRetailPrice,
                'airtime_purchase',
                'Airtime Recharge: ₦'.number_format($faceAmount)." {$network->name} for {$phone}",
                [
                    'phone' => $phone,
                    'network' => $network->name,
                    'face_amount' => $faceAmount,
                    'retail_price' => $customerRetailPrice,
                ],
                $txReference
            );

            // 2. Debit Store Main Wallet (Wholesale Cost)
            $this->walletService->debit(
                $storeMainWallet,
                $storeWholesaleCost,
                'wholesale_airtime_debit',
                'Wholesale Airtime Purchase: ₦'.number_format($faceAmount)." {$network->name} for customer #{$customer->id}",
                [
                    'customer_id' => $customer->id,
                    'phone' => $phone,
                    'face_amount' => $faceAmount,
                    'wholesale_cost' => $storeWholesaleCost,
                ],
                'WS_'.$txReference
            );

            // 3. Credit Store Profit Wallet (Profit Margin)
            if ($profitMargin > 0) {
                $storeProfitWallet = $store->profitWallet();
                $this->walletService->credit(
                    $storeProfitWallet,
                    $profitMargin,
                    'store_airtime_profit',
                    'Profit Margin from Airtime Sale: ₦'.number_format($faceAmount)." {$network->name}",
                    [
                        'customer_id' => $customer->id,
                        'face_amount' => $faceAmount,
                        'retail_price' => $customerRetailPrice,
                        'wholesale_cost' => $storeWholesaleCost,
                    ],
                    'PRF_'.$txReference
                );
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Airtime Purchase Wallet Debit Error: '.$e->getMessage());
            throw new \Exception('Failed to process wallet transaction: '.$e->getMessage());
        }

        // 6. Call Provider Driver (VTULab)
        $apiResult = $this->vtuLabService->purchaseAirtime($network->slug ?? $network->id, $faceAmount, $phone, $txReference);

        // 7. Check Provider Response
        if ($apiResult['success'] || $apiResult['pending']) {
            $status = $apiResult['pending'] ? 'pending' : 'success';

            $transaction = Transaction::create([
                'user_id' => $customer->id,
                'store_id' => $store->id,
                'service_type' => 'airtime',
                'amount' => $customerRetailPrice,
                'cost_price' => $storeWholesaleCost,
                'profit' => $profitMargin,
                'recipient' => $phone,
                'status' => $status,
                'reference' => $txReference,
                'api_response' => $apiResult['raw'] ?? [],
            ]);

            try {
                app(ReferralService::class)->checkAndReward($customer, 'purchase', $customerRetailPrice);
            } catch (\Throwable $refErr) {
                Log::warning('Referral purchase check error: '.$refErr->getMessage());
            }

            return [
                'success' => true,
                'status' => $status,
                'message' => $apiResult['message'] ?? 'Airtime recharge successful.',
                'reference' => $txReference,
                'transaction_id' => $transaction->id,
            ];
        }

        // 8. Auto-Refund on API Delivery Failure
        $errorMessage = $apiResult['message'] ?? 'Provider airtime delivery failed.';
        Log::warning("VTULab Delivery Failed for {$txReference}: {$errorMessage}. Initiating Auto-Refund...");

        try {
            // Refund Customer Wallet
            $this->walletService->refund(
                $customerWallet,
                $customerRetailPrice,
                "Auto-Refund: Airtime recharge failed ({$errorMessage})",
                ['failed_reference' => $txReference, 'reason' => $errorMessage],
                'REF_'.$txReference
            );

            // Refund Store Main Wallet
            $this->walletService->credit(
                $storeMainWallet,
                $storeWholesaleCost,
                'wholesale_refund',
                'Auto-Refund Store Wholesale: Airtime recharge failed',
                ['failed_reference' => $txReference],
                'REF_WS_'.$txReference
            );

            // Reverse Store Profit if credited
            if ($profitMargin > 0) {
                $storeProfitWallet = $store->profitWallet();
                if ((float) $storeProfitWallet->balance >= $profitMargin) {
                    $this->walletService->debit(
                        $storeProfitWallet,
                        $profitMargin,
                        'profit_reversal',
                        'Profit Reversal: Airtime recharge failed',
                        ['failed_reference' => $txReference],
                        'REV_PRF_'.$txReference
                    );
                }
            }
        } catch (\Throwable $refundError) {
            Log::critical("Auto-Refund Critical Failure for Airtime Reference {$txReference}: ".$refundError->getMessage());
        }

        Transaction::create([
            'user_id' => $customer->id,
            'store_id' => $store->id,
            'service_type' => 'airtime',
            'amount' => $customerRetailPrice,
            'cost_price' => $storeWholesaleCost,
            'profit' => 0.00,
            'recipient' => $phone,
            'status' => 'failed',
            'reference' => $txReference,
            'api_response' => $apiResult['raw'] ?? [],
        ]);

        return [
            'success' => false,
            'status' => 'failed',
            'message' => $errorMessage,
            'reference' => $txReference,
        ];
    }
}
