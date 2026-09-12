<?php

namespace App\Services\Vtu;

use App\Models\DataPlan;
use App\Models\PlanDataPrice;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreDataPlan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ReferralService;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataService
{
    protected WalletService $walletService;

    protected VtuLabService $vtuLabService;

    public function __construct(WalletService $walletService, VtuLabService $vtuLabService)
    {
        $this->walletService = $walletService;
        $this->vtuLabService = $vtuLabService;
    }

    /**
     * Execute an atomic Data Plan purchase for a customer on a store storefront.
     *
     * @param  string  $phoneNumber  (11-digit recipient phone number)
     */
    public function buyDataPlan(User $customer, StoreDataPlan $storeDataPlan, string $phoneNumber): array
    {
        // Normalize phone number
        $phone = preg_replace('/\D+/', '', $phoneNumber);
        if (strlen($phone) !== 11) {
            throw new \InvalidArgumentException('Please enter a valid 11-digit phone number (e.g. 08012345678).');
        }

        /** @var DataPlan $dataPlan */
        $dataPlan = $storeDataPlan->dataPlan;
        if (! $dataPlan || ! $dataPlan->is_active) {
            throw new \Exception('Selected data plan is currently unavailable.');
        }

        $network = $dataPlan->network;
        $networkName = $network ? $network->name : 'MTN';
        $planId = $dataPlan->plan_code ?? $dataPlan->id;

        // Resolve store & pricing
        $store = (method_exists($customer, 'store') ? $customer->store : null)
            ?? Store::where('owner_id', $customer->id)->first()
            ?? Store::first();

        if (! $store) {
            throw new \Exception('Store context missing for user purchase.');
        }

        // Resolve pricing & wholesale ledger
        $customerRetailPrice = (float) ($storeDataPlan->selling_price ?? $dataPlan->default_retail_price);
        $facePrice = (float) ($dataPlan->default_retail_price ?? $customerRetailPrice);
        $discountAmount = max(0, round($facePrice - $customerRetailPrice, 2));

        $subscription = $store->subscription;
        $tierPrice = null;
        if ($subscription && $subscription->plan_id) {
            $tierPrice = PlanDataPrice::where('plan_id', $subscription->plan_id)
                ->where('data_plan_id', $dataPlan->id)
                ->first();
        }

        $resellerWholesaleCost = $tierPrice && $tierPrice->wholesale_price !== null
            ? (float) $tierPrice->wholesale_price
            : (float) ($dataPlan->selling_price ?? $dataPlan->cost_price ?? $customerRetailPrice);

        $vendorCost = (float) ($dataPlan->cost_price ?? $resellerWholesaleCost);
        $profitMargin = max(0, round($customerRetailPrice - $resellerWholesaleCost, 2));
        $platformProfit = max(0, round($resellerWholesaleCost - $vendorCost, 2));

        $customerWallet = $customer->wallet('main');
        if (! $customerWallet || (float) $customerWallet->balance < $customerRetailPrice) {
            throw new \Exception('Insufficient wallet balance. Please fund your account to purchase this plan.');
        }

        $storeMainWallet = $store->mainWallet();
        if ((float) $storeMainWallet->balance < $resellerWholesaleCost) {
            Log::warning("Store #{$store->id} ({$store->name}) vending wallet balance low (₦{$storeMainWallet->balance}) for wholesale cost (₦{$resellerWholesaleCost}).");
            throw new \Exception('Unable to process this order. Please contact store support for assistance.');
        }

        // Generate dynamic Store-prefixed reference (e.g. DEMO_STORE_DATA_20260904165120_8FA29X)
        $storePrefix = strtoupper(Str::slug($store->name ?? 'AFF', '_'));
        $txReference = $storePrefix.'_DATA_'.date('YmdHis').'_'.strtoupper(Str::random(6));

        DB::beginTransaction();
        try {
            // 1. Lock & Debit Customer Wallet
            $customerTx = $this->walletService->debit(
                $customerWallet,
                $customerRetailPrice,
                'data_purchase',
                "Data Purchase: {$dataPlan->name} ({$networkName}) for {$phone}",
                [
                    'phone' => $phone,
                    'network' => $networkName,
                    'plan_id' => $dataPlan->id,
                    'store_data_plan_id' => $storeDataPlan->id,
                ],
                $txReference
            );

            // 2. Lock & Debit Store Main Wallet for Wholesale Cost only
            $storeMainTx = $this->walletService->debit(
                $storeMainWallet,
                $resellerWholesaleCost,
                'wholesale_cost',
                "Wholesale Cost: {$dataPlan->name} for customer #{$customer->id}",
                [
                    'customer_id' => $customer->id,
                    'phone' => $phone,
                    'retail_price' => $customerRetailPrice,
                    'wholesale_cost' => $resellerWholesaleCost,
                ],
                'WS_'.$txReference
            );

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Data Purchase Wallet Debit Error: '.$e->getMessage());
            throw new \Exception('Failed to process wallet transaction: '.$e->getMessage());
        }

        // 4. Call VTULab API Driver with exact signature (planId, phone, reference)
        $apiResult = $this->vtuLabService->purchaseData($planId, $phone, $txReference);

        // 5. Check API Result
        if ($apiResult['success'] || $apiResult['pending']) {
            $status = $apiResult['pending'] ? 'pending' : 'successful';
            $dataServiceId = Service::where('key', 'data')->value('id');

            // Profit Allocation: ONLY sweep and credit profit if confirmed successful!
            // If status is 'pending', the profit remains safely in Store Main Wallet until delivery is confirmed.
            if ($status === 'successful' && $profitMargin > 0) {
                try {
                    // 1. Debit profit sweep from Store Main Wallet
                    $this->walletService->debit(
                        $storeMainWallet,
                        $profitMargin,
                        'profit_sweep',
                        "Profit Allocation: {$dataPlan->name} ({$phone})",
                        [
                            'customer_id' => $customer->id,
                            'retail_price' => $customerRetailPrice,
                            'wholesale_cost' => $resellerWholesaleCost,
                            'profit_margin' => $profitMargin,
                        ],
                        'SWP_'.$txReference
                    );

                    // 2. Credit Store Profit Wallet (Withdrawable)
                    $storeProfitWallet = $store->profitWallet();
                    $this->walletService->credit(
                        $storeProfitWallet,
                        $profitMargin,
                        'earned_profit',
                        "Earned Profit: {$dataPlan->name} ({$phone})",
                        [
                            'customer_id' => $customer->id,
                            'retail_price' => $customerRetailPrice,
                            'wholesale_cost' => $resellerWholesaleCost,
                            'profit_margin' => $profitMargin,
                        ],
                        'PRF_'.$txReference
                    );
                } catch (\Throwable $profitErr) {
                    Log::error("Failed to sweep profit for {$txReference}: ".$profitErr->getMessage());
                }
            }

            // Create Transaction audit log with full financial ledger
            $transaction = Transaction::create([
                'user_id' => $customer->id,
                'store_id' => $store->id,
                'service_id' => $dataServiceId,
                'service_type' => 'data',
                'amount' => $facePrice,
                'discount' => $discountAmount,
                'amount_paid' => $customerRetailPrice,
                'cost_price' => $resellerWholesaleCost,
                'vendor_cost' => $vendorCost,
                'profit' => $profitMargin,
                'platform_profit' => $platformProfit,
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
                'message' => $apiResult['message'],
                'reference' => $txReference,
                'transaction_id' => $transaction->id,
            ];
        }

        // 6. Handle Delivery Failure (Instant Auto-Refund Engine)
        $errorMessage = $apiResult['message'] ?? 'Provider data delivery failed.';
        Log::warning("VTULab Delivery Failed for {$txReference}: {$errorMessage}. Initiating Auto-Refund...");

        try {
            // Auto-Refund Customer Wallet
            $this->walletService->refund(
                $customerWallet,
                $customerRetailPrice,
                "Auto-Refund: Data purchase failed ({$errorMessage})",
                ['failed_reference' => $txReference, 'reason' => $errorMessage],
                'REF_'.$txReference
            );

            // Auto-Refund Store Main Wallet (Wholesale Cost)
            $this->walletService->credit(
                $storeMainWallet,
                $resellerWholesaleCost,
                'wholesale_refund',
                'Auto-Refund Store Wholesale: Data purchase failed',
                ['failed_reference' => $txReference],
                'REF_WS_'.$txReference
            );

            $dataServiceId = Service::where('key', 'data')->value('id');

            // Log Failed Transaction with full financial ledger
            $transaction = Transaction::create([
                'user_id' => $customer->id,
                'store_id' => $store->id,
                'service_id' => $dataServiceId,
                'service_type' => 'data',
                'amount' => $facePrice,
                'discount' => $discountAmount,
                'amount_paid' => $customerRetailPrice,
                'cost_price' => $resellerWholesaleCost,
                'vendor_cost' => $vendorCost,
                'profit' => 0.00,
                'platform_profit' => 0.00,
                'recipient' => $phone,
                'status' => 'failed',
                'reference' => $txReference,
                'api_response' => $apiResult['raw'] ?? [],
            ]);

            return [
                'success' => false,
                'status' => 'failed',
                'message' => "Data delivery failed: {$errorMessage}. Your wallet has been automatically refunded.",
                'reference' => $txReference,
                'refunded' => true,
            ];
        } catch (\Throwable $refundError) {
            Log::critical("CRITICAL: Auto-Refund Exception for {$txReference}: ".$refundError->getMessage());
            throw new \Exception('Data delivery failed and refund exception occurred: '.$refundError->getMessage());
        }
    }
}
