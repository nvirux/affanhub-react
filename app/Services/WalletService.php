<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
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
        ?string $reference = null
    ): WalletTransaction {
        return DB::transaction(function () use ($wallet, $amount, $category, $description, $meta, $reference) {
            /** @var Wallet $lockedWallet */
            $lockedWallet = Wallet::where('id', $wallet->id)->lockForUpdate()->firstOrFail();

            $balanceBefore = (float) $lockedWallet->balance;

            if ($balanceBefore < $amount) {
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
     * Auto-credit store main wallet when customer deposits via bank transfer.
     */
    public function handleCustomerBankDeposit(
        Wallet $customerWallet,
        Wallet $storeMainWallet,
        float $amount,
        string $reference,
        array $depositMeta = []
    ): array {
        return DB::transaction(function () use ($customerWallet, $storeMainWallet, $amount, $reference, $depositMeta) {
            $custTx = $this->credit(
                $customerWallet,
                $amount,
                'bank_transfer_deposit',
                'Customer Bank Transfer Deposit',
                array_merge($depositMeta, ['channel' => 'virtual_account']),
                'DEP_'.$reference
            );

            $storeTx = $this->credit(
                $storeMainWallet,
                $amount,
                'store_auto_credit',
                'Automated Customer Deposit Wholesale Pass-through Credit',
                array_merge($depositMeta, ['channel' => 'store_auto_pass_through']),
                'ST_DEP_'.$reference
            );

            return [
                'customer_transaction' => $custTx,
                'store_transaction' => $storeTx,
            ];
        });
    }
}
