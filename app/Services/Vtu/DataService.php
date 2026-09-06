<?php

namespace App\Services\Vtu;

use App\Models\DataPlan;
use App\Models\Store;
use App\Models\StoreDataPlan;
use App\Models\Transaction;
use App\Models\User;
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
     * @param User $customer
     * @param StoreDataPlan $storeDataPlan
     * @param string $phoneNumber (11-digit recipient phone number)
     * @return array
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

        $customerRetailPrice = (float) ($storeDataPlan->custom_selling_price ?? $dataPlan->default_retail_price);
        $resellerWholesaleCost = (float) $storeDataPlan->getWholesaleCostPrice();
        $profitMargin = max(0, $customerRetailPrice - $resellerWholesaleCost);

        $customerWallet = $customer->wallet('main');
        if (! $customerWallet || (float) $customerWallet->balance < $customerRetailPrice) {
            throw new \Exception('Insufficient wallet balance. Please fund your account to purchase this plan.');
        }

        $storeMainWallet = $store->mainWallet();
        if ((float) $storeMainWallet->balance < $resellerWholesaleCost) {
            throw new \Exception('Store wholesale balance low. Please contact store support.');
        }

        // Generate dynamic Store-prefixed reference (e.g. DEMO_STORE_DATA_20260904165120_8FA29X)
        $storePrefix = strtoupper(Str::slug($store->name ?? 'AFF', '_'));
        $txReference = $storePrefix . '_DATA_' . date('YmdHis') . '_' . strtoupper(Str::random(6));

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

            // 2. Lock & Debit Store Main Wallet (Wholesale Cost)
            $storeMainTx = $this->walletService->debit(
                $storeMainWallet,
                $resellerWholesaleCost,
                'wholesale_data_debit',
                "Wholesale Data Purchase: {$dataPlan->name} for customer #{$customer->id}",
                [
                    'customer_id' => $customer->id,
                    'phone' => $phone,
                    'retail_price' => $customerRetailPrice,
                    'wholesale_cost' => $resellerWholesaleCost,
                ],
                'WS_' . $txReference
            );

            // 3. Credit Store Profit Wallet (Profit Margin)
            if ($profitMargin > 0) {
                $storeProfitWallet = $store->profitWallet();
                $this->walletService->credit(
                    $storeProfitWallet,
                    $profitMargin,
                    'store_data_profit',
                    "Profit Margin from Data Sale: {$dataPlan->name}",
                    [
                        'customer_id' => $customer->id,
                        'retail_price' => $customerRetailPrice,
                        'wholesale_cost' => $resellerWholesaleCost,
                    ],
                    'PRF_' . $txReference
                );
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Data Purchase Wallet Debit Error: ' . $e->getMessage());
            throw new \Exception('Failed to process wallet transaction: ' . $e->getMessage());
        }

        // 4. Call VTULab API Driver with exact signature (planId, phone, reference)
        $apiResult = $this->vtuLabService->purchaseData($planId, $phone, $txReference);

        // 5. Check API Result
        if ($apiResult['success'] || $apiResult['pending']) {
            $status = $apiResult['pending'] ? 'pending' : 'successful';

            // Create Transaction audit log
            $transaction = Transaction::create([
                'user_id' => $customer->id,
                'store_id' => $store->id,
                'type' => 'data_purchase',
                'amount' => $customerRetailPrice,
                'wholesale_cost' => $resellerWholesaleCost,
                'profit' => $profitMargin,
                'status' => $status,
                'reference' => $txReference,
                'meta' => [
                    'phone' => $phone,
                    'network' => $networkName,
                    'plan_name' => $dataPlan->name,
                    'provider_response' => $apiResult['raw'],
                ],
            ]);

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
                'REF_' . $txReference
            );

            // Auto-Refund Store Main Wallet
            $this->walletService->credit(
                $storeMainWallet,
                $resellerWholesaleCost,
                'wholesale_refund',
                "Auto-Refund Store Wholesale: Data purchase failed",
                ['failed_reference' => $txReference],
                'REF_WS_' . $txReference
            );

            // Reverse Store Profit if credited
            if ($profitMargin > 0) {
                $storeProfitWallet = $store->profitWallet();
                if ((float) $storeProfitWallet->balance >= $profitMargin) {
                    $this->walletService->debit(
                        $storeProfitWallet,
                        $profitMargin,
                        'profit_reversal',
                        "Reversal Store Profit: Data purchase failed",
                        ['failed_reference' => $txReference],
                        'REV_PRF_' . $txReference
                    );
                }
            }

            // Log Failed Transaction
            $transaction = Transaction::create([
                'user_id' => $customer->id,
                'store_id' => $store->id,
                'type' => 'data_purchase',
                'amount' => $customerRetailPrice,
                'wholesale_cost' => $resellerWholesaleCost,
                'profit' => 0.00,
                'status' => 'failed',
                'reference' => $txReference,
                'meta' => [
                    'phone' => $phone,
                    'network' => $networkName,
                    'plan_name' => $dataPlan->name,
                    'error_reason' => $errorMessage,
                    'refunded' => true,
                ],
            ]);

            return [
                'success' => false,
                'status' => 'failed',
                'message' => "Data delivery failed: {$errorMessage}. Your wallet has been automatically refunded.",
                'reference' => $txReference,
                'refunded' => true,
            ];
        } catch (\Throwable $refundError) {
            Log::critical("CRITICAL: Auto-Refund Exception for {$txReference}: " . $refundError->getMessage());
            throw new \Exception("Data delivery failed and refund exception occurred: " . $refundError->getMessage());
        }
    }
}
