<?php

namespace App\Services;

use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\Notification\MerchantNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletService
{
    /**
     * Credit a wallet atomically and record an immutable ledger entry.
     */
    public function credit(
        Wallet $wallet,
        float $amount,
        string $category,
        string $description,
        ?array $meta = [],
        ?string $reference = null
    ): WalletTransaction {
        $tx = DB::transaction(function () use ($wallet, $amount, $category, $description, $meta, $reference) {
            /** @var Wallet $lockedWallet */
            $lockedWallet = Wallet::where('id', $wallet->id)->lockForUpdate()->firstOrFail();

            $balanceBefore = (float) $lockedWallet->balance;
            $balanceAfter = $balanceBefore + $amount;

            $lockedWallet->balance = $balanceAfter;
            $lockedWallet->save();

            $ref = $reference ?? ('WT_'.strtoupper(Str::random(12)));

            return WalletTransaction::create([
                'wallet_id' => $lockedWallet->id,
                'type' => 'credit',
                'category' => $category,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference' => $ref,
                'description' => $description,
                'status' => 'success',
                'meta' => $meta,
            ]);
        });

        if (in_array($category, ['deposit', 'bank_transfer_deposit'], true) && $wallet->holder instanceof User) {
            try {
                app(ReferralService::class)->checkAndReward($wallet->holder, 'deposit', $amount);
            } catch (\Throwable $e) {
                Log::warning('Referral deposit check error: '.$e->getMessage());
            }

            try {
                app(MerchantNotificationService::class)
                    ->notifyCustomerWalletFunded($wallet->holder, $amount, $tx->reference, $description);
            } catch (\Throwable $e) {
                Log::warning('Merchant wallet funded notification error: '.$e->getMessage());
            }
        }

        return $tx;
    }

    /**
     * Refund a wallet atomically and record an immutable ledger entry.
     */
    public function refund(
        Wallet $wallet,
        float $amount,
        string $description,
        ?array $meta = [],
        ?string $reference = null
    ): WalletTransaction {
        return $this->credit(
            $wallet,
            $amount,
            'refund',
            $description,
            $meta,
            $reference
        );
    }

    /**
     * Debit a wallet atomically and record an immutable ledger entry.
     */
    public function debit(
        Wallet $wallet,
        float $amount,
        string $category,
        string $description,
        ?array $meta = [],
        ?string $reference = null,
        bool $allowNegative = false
    ): WalletTransaction {
        return DB::transaction(function () use ($wallet, $amount, $category, $description, $meta, $reference, $allowNegative) {
            /** @var Wallet $lockedWallet */
            $lockedWallet = Wallet::where('id', $wallet->id)->lockForUpdate()->firstOrFail();

            $balanceBefore = (float) $lockedWallet->balance;

            if (! $allowNegative && $balanceBefore < $amount) {
                throw new \InvalidArgumentException(sprintf(
                    'Insufficient wallet balance. Available: ₦%s, Required: ₦%s.',
                    number_format($balanceBefore, 2),
                    number_format($amount, 2)
                ));
            }

            $balanceAfter = $balanceBefore - $amount;

            $lockedWallet->balance = $balanceAfter;
            $lockedWallet->save();

            $ref = $reference ?? ('WT_'.strtoupper(Str::random(12)));

            return WalletTransaction::create([
                'wallet_id' => $lockedWallet->id,
                'type' => 'debit',
                'category' => $category,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference' => $ref,
                'description' => $description,
                'status' => 'success',
                'meta' => $meta,
            ]);
        });
    }

    /**
     * Auto-credit customer wallet and store main wallet when customer deposits via bank transfer.
     */
    public function handleCustomerBankDeposit(
        Wallet $customerWallet,
        Wallet $storeMainWallet,
        float $customerAmount,
        string $reference,
        array $depositMeta = [],
        ?float $storeAmount = null,
        ?string $customerDescription = null
    ): array {
        $finalStoreAmount = $storeAmount !== null ? $storeAmount : $customerAmount;

        return DB::transaction(function () use ($customerWallet, $storeMainWallet, $customerAmount, $finalStoreAmount, $reference, $depositMeta, $customerDescription) {
            $custTx = $this->credit(
                $customerWallet,
                $customerAmount,
                'bank_transfer_deposit',
                $customerDescription ?? 'Customer Bank Transfer Deposit',
                array_merge($depositMeta, ['channel' => 'virtual_account', 'credited_amount' => $customerAmount]),
                'DEP_'.$reference
            );

            $storeTx = $this->credit(
                $storeMainWallet,
                $finalStoreAmount,
                'store_auto_credit',
                'Automated Customer Deposit Wholesale Pass-through Credit',
                array_merge($depositMeta, ['channel' => 'store_auto_pass_through', 'credited_amount' => $finalStoreAmount]),
                'ST_DEP_'.$reference
            );

            return [
                'customer_transaction' => $custTx,
                'store_transaction' => $storeTx,
            ];
        });
    }

    /**
     * Atomically sweep wholesale profit margin from Store Main Wallet to Store Profit Wallet.
     * Used across VTU (Airtime, Data) and Identity Services (NIN, BVN).
     *
     * @return array{swept: bool, sweep_tx: ?WalletTransaction, profit_tx: ?WalletTransaction}
     */
    public function sweepWholesaleProfit(
        Wallet $storeMainWallet,
        Wallet $storeProfitWallet,
        float $profitMargin,
        string $reference,
        string $serviceName,
        ?int $customerId = null,
        array $meta = []
    ): array {
        if ($profitMargin <= 0) {
            return ['swept' => false, 'sweep_tx' => null, 'profit_tx' => null];
        }

        return DB::transaction(function () use ($storeMainWallet, $storeProfitWallet, $profitMargin, $reference, $serviceName, $customerId, $meta) {
            $sweepTx = $this->debit(
                $storeMainWallet,
                $profitMargin,
                'profit_sweep',
                "Profit Allocation: {$serviceName}",
                array_merge($meta, [
                    'order_reference' => $reference,
                    'service' => $serviceName,
                    'customer_id' => $customerId,
                    'profit_margin' => $profitMargin,
                ]),
                'SWP_'.$reference
            );

            $profitTx = $this->credit(
                $storeProfitWallet,
                $profitMargin,
                'earned_profit',
                "Earned Profit: {$serviceName}".($customerId ? " for customer #{$customerId}" : ''),
                array_merge($meta, [
                    'order_reference' => $reference,
                    'service' => $serviceName,
                    'customer_id' => $customerId,
                    'profit_margin' => $profitMargin,
                ]),
                'PRF_'.$reference
            );

            return [
                'swept' => true,
                'sweep_tx' => $sweepTx,
                'profit_tx' => $profitTx,
            ];
        });
    }

    /**
     * Settle merchant profit for manual services (CAC, Modifications).
     * If the merchant's Main Wallet has sufficient balance, debits Main and credits Profit Wallet.
     * If Main Wallet balance is insufficient (e.g. ₦0 because merchant already spent it),
     * safely skips the transfer and returns a structured status with reasons.
     *
     * @return array{settled: bool, reason: ?string, sweep_tx: ?WalletTransaction, profit_tx: ?WalletTransaction}
     */
    public function settleManualServiceProfit(
        Store $store,
        float $amount,
        string $reference,
        string $serviceName,
        string $serviceCategoryKey = 'service',
        ?int $customerId = null,
        array $meta = []
    ): array {
        $storeMainWallet = $store->mainWallet();
        $profitWallet = $store->profitWallet();

        if (! $storeMainWallet || ! $profitWallet || $amount <= 0) {
            return [
                'settled' => false,
                'reason' => 'invalid_wallet_or_amount',
                'sweep_tx' => null,
                'profit_tx' => null,
            ];
        }

        return DB::transaction(function () use ($storeMainWallet, $profitWallet, $amount, $reference, $serviceName, $serviceCategoryKey, $customerId, $meta) {
            /** @var Wallet $lockedMainWallet */
            $lockedMainWallet = Wallet::where('id', $storeMainWallet->id)->lockForUpdate()->firstOrFail();

            if ((float) $lockedMainWallet->balance < $amount) {
                Log::info("Manual service profit settlement skipped for {$reference}: Store Main Wallet balance (₦".number_format((float) $lockedMainWallet->balance, 2).') is less than order amount (₦'.number_format($amount, 2).').');

                return [
                    'settled' => false,
                    'reason' => 'insufficient_main_wallet_balance',
                    'sweep_tx' => null,
                    'profit_tx' => null,
                ];
            }

            $sweepTx = $this->debit(
                $lockedMainWallet,
                $amount,
                "{$serviceCategoryKey}_settlement_out",
                "Settlement transfer to profit for {$serviceName} (#{$reference})",
                array_merge($meta, [
                    'order_reference' => $reference,
                    'service' => $serviceName,
                    'customer_id' => $customerId,
                ]),
                'SETTLE_'.$reference
            );

            $profitTx = $this->credit(
                $profitWallet,
                $amount,
                "{$serviceCategoryKey}_profit",
                "Profit from {$serviceName} order #{$reference}",
                array_merge($meta, [
                    'order_reference' => $reference,
                    'customer_id' => $customerId,
                ]),
                'PRF_'.$reference
            );

            return [
                'settled' => true,
                'reason' => null,
                'sweep_tx' => $sweepTx,
                'profit_tx' => $profitTx,
            ];
        });
    }

    /**
     * Atomically reverse an instant service transaction when upstream provider fails.
     * Refunds customer retail cost and refunds store wholesale cost.
     */
    public function reverseInstantService(
        Wallet $customerWallet,
        ?Wallet $storeMainWallet,
        float $retailAmount,
        float $wholesaleAmount,
        string $reference,
        string $serviceDescription,
        string $failureReason
    ): void {
        DB::transaction(function () use ($customerWallet, $storeMainWallet, $retailAmount, $wholesaleAmount, $reference, $serviceDescription, $failureReason) {
            if ($retailAmount > 0) {
                $this->refund(
                    $customerWallet,
                    $retailAmount,
                    "Auto-Refund: {$serviceDescription} purchase failed",
                    ['failed_reference' => $reference, 'reason' => $failureReason],
                    'REF_'.$reference
                );
            }

            if ($storeMainWallet && $wholesaleAmount > 0) {
                $this->credit(
                    $storeMainWallet,
                    $wholesaleAmount,
                    'wholesale_refund',
                    "Auto-Refund Store Wholesale: {$serviceDescription} failed",
                    ['failed_reference' => $reference, 'reason' => $failureReason],
                    'WS_REF_'.$reference
                );
            }
        });
    }
}
