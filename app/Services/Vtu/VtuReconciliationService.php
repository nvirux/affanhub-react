<?php

namespace App\Services\Vtu;

use App\Models\Transaction;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VtuReconciliationService
{
    public function __construct(
        protected VtuLabService $vtuLabService,
        protected WalletService $walletService
    ) {}

    /**
     * Reconcile a single transaction with VTULab API.
     *
     * @return array ['success' => bool, 'status' => string, 'message' => string]
     */
    public function reconcile(Transaction $transaction): array
    {
        // Only reconcile pending or processing transactions
        if (! in_array(strtolower($transaction->status), ['pending', 'processing'])) {
            return [
                'success' => true,
                'status' => $transaction->status,
                'message' => "Transaction is already {$transaction->status}.",
            ];
        }

        $result = $this->vtuLabService->queryTransaction($transaction->reference);

        if (! $result['success'] && ($result['status'] ?? '') === 'not_found') {
            return [
                'success' => false,
                'status' => 'pending',
                'message' => 'Transaction not yet found on provider. Will re-check on next cycle.',
            ];
        }

        if (! $result['success'] && in_array($result['status'] ?? '', ['error', 'unknown'])) {
            return [
                'success' => false,
                'status' => 'pending',
                'message' => $result['message'] ?? 'Unable to connect to provider.',
            ];
        }

        $newStatus = $result['status'];

        if ($newStatus === 'successful') {
            return $this->finalizeSuccess($transaction, $result['raw'] ?? []);
        }

        if ($newStatus === 'failed') {
            return $this->finalizeFailure($transaction, $result['message'] ?? 'Provider reported transaction failure.', $result['raw'] ?? []);
        }

        // Still pending
        return [
            'success' => true,
            'status' => 'pending',
            'message' => 'Transaction is still processing at provider.',
        ];
    }

    /**
     * Finalize transaction as successful and allocate merchant profit.
     */
    protected function finalizeSuccess(Transaction $transaction, array $rawResponse = []): array
    {
        return DB::transaction(function () use ($transaction, $rawResponse) {
            $existingApiResponse = is_array($transaction->api_response) ? $transaction->api_response : [];
            $existingApiResponse['reconciled_at'] = now()->toIso8601String();
            $existingApiResponse['reconciliation_result'] = $rawResponse;

            $transaction->update([
                'status' => 'successful',
                'api_response' => $existingApiResponse,
            ]);

            // Allocate Merchant Profit if not yet allocated
            $profitMargin = (float) $transaction->profit;
            $swpRef = 'SWP_'.$transaction->reference;
            $alreadySwept = WalletTransaction::where('reference', $swpRef)->exists();

            if (! $alreadySwept && $profitMargin > 0 && $transaction->store) {
                try {
                    $store = $transaction->store;
                    $storeMainWallet = $store->mainWallet();
                    $storeProfitWallet = $store->profitWallet();

                    if ($storeMainWallet && $storeProfitWallet) {
                        // 1. Debit profit sweep from Store Main Wallet
                        $this->walletService->debit(
                            $storeMainWallet,
                            $profitMargin,
                            'profit_sweep',
                            "Profit Allocation: {$transaction->service_type} ({$transaction->recipient})",
                            [
                                'customer_id' => $transaction->user_id,
                                'amount_paid' => $transaction->amount_paid,
                                'cost_price' => $transaction->cost_price,
                                'profit_margin' => $profitMargin,
                            ],
                            $swpRef
                        );

                        // 2. Credit Store Profit Wallet (Withdrawable)
                        $this->walletService->credit(
                            $storeProfitWallet,
                            $profitMargin,
                            'earned_profit',
                            "Earned Profit: {$transaction->service_type} ({$transaction->recipient})",
                            [
                                'customer_id' => $transaction->user_id,
                                'amount_paid' => $transaction->amount_paid,
                                'cost_price' => $transaction->cost_price,
                                'profit_margin' => $profitMargin,
                            ],
                            'PRF_'.$transaction->reference
                        );
                    }
                } catch (\Throwable $profitErr) {
                    Log::error("Failed to sweep profit during reconciliation for {$transaction->reference}: ".$profitErr->getMessage());
                }
            }

            return [
                'success' => true,
                'status' => 'successful',
                'message' => 'Transaction successfully verified and completed.',
            ];
        });
    }

    /**
     * Finalize transaction as failed and refund customer + store wholesale cost.
     */
    protected function finalizeFailure(Transaction $transaction, string $reason, array $rawResponse = []): array
    {
        return DB::transaction(function () use ($transaction, $reason, $rawResponse) {
            $refRef = 'REF_'.$transaction->reference;
            $alreadyRefunded = WalletTransaction::where('reference', $refRef)->exists();

            if (! $alreadyRefunded) {
                // 1. Auto-Refund Customer Wallet
                $customer = $transaction->user;
                if ($customer && (float) $transaction->amount_paid > 0) {
                    try {
                        $customerWallet = $customer->wallet('main');
                        $this->walletService->refund(
                            $customerWallet,
                            (float) $transaction->amount_paid,
                            "Auto-Refund: {$transaction->service_type} purchase failed ({$reason})",
                            ['failed_reference' => $transaction->reference, 'reason' => $reason],
                            $refRef
                        );
                    } catch (\Throwable $refundErr) {
                        Log::critical("Customer Auto-Refund Failed during reconciliation for {$transaction->reference}: ".$refundErr->getMessage());
                    }
                }

                // 2. Auto-Refund Store Main Wallet (Wholesale Cost)
                if ($transaction->store && (float) $transaction->cost_price > 0) {
                    try {
                        $storeMainWallet = $transaction->store->mainWallet();
                        if ($storeMainWallet) {
                            $this->walletService->credit(
                                $storeMainWallet,
                                (float) $transaction->cost_price,
                                'wholesale_refund',
                                "Auto-Refund Store Wholesale: {$transaction->service_type} failed",
                                ['failed_reference' => $transaction->reference],
                                'REF_WS_'.$transaction->reference
                            );
                        }
                    } catch (\Throwable $storeRefundErr) {
                        Log::critical("Store Wholesale Auto-Refund Failed for {$transaction->reference}: ".$storeRefundErr->getMessage());
                    }
                }
            }

            $existingApiResponse = is_array($transaction->api_response) ? $transaction->api_response : [];
            $existingApiResponse['reconciled_at'] = now()->toIso8601String();
            $existingApiResponse['reconciliation_result'] = $rawResponse;
            $existingApiResponse['failure_reason'] = $reason;

            $transaction->update([
                'status' => 'failed',
                'api_response' => $existingApiResponse,
            ]);

            return [
                'success' => true,
                'status' => 'failed',
                'message' => 'Transaction marked as failed and wallet refunded.',
            ];
        });
    }

    /**
     * Administratively mark a previously failed transaction as delivered and re-debit both customer and store.
     *
     * @return array ['success' => bool, 'message' => string]
     */
    public function markAsDelivered(Transaction $transaction, bool $forceAllowNegative = true): array
    {
        if (in_array(strtolower($transaction->status), ['successful', 'completed'])) {
            return [
                'success' => false,
                'message' => 'Transaction is already marked as successful.',
            ];
        }

        return DB::transaction(function () use ($transaction, $forceAllowNegative) {
            $customer = $transaction->user;
            $store = $transaction->store;
            $rdbCustomerRef = 'RDB_'.$transaction->reference;
            $rdbStoreRef = 'RDB_WS_'.$transaction->reference;

            // 1. Re-debit customer wallet if previously refunded
            if ($customer && (float) $transaction->amount_paid > 0) {
                $alreadyDebited = WalletTransaction::where('reference', $rdbCustomerRef)->exists();
                if (! $alreadyDebited) {
                    $customerWallet = $customer->wallet('main');
                    $this->walletService->debit(
                        $customerWallet,
                        (float) $transaction->amount_paid,
                        'manual_adjustment',
                        "Manual Settlement: Re-debit for delivered {$transaction->service_type} ({$transaction->recipient})",
                        ['transaction_id' => $transaction->id, 'reference' => $transaction->reference],
                        $rdbCustomerRef,
                        $forceAllowNegative
                    );
                }
            }

            // 2. Re-debit store main wallet for wholesale cost if previously refunded
            if ($store && (float) $transaction->cost_price > 0) {
                $alreadyStoreDebited = WalletTransaction::where('reference', $rdbStoreRef)->exists();
                if (! $alreadyStoreDebited) {
                    $storeMainWallet = $store->mainWallet();
                    if ($storeMainWallet) {
                        $this->walletService->debit(
                            $storeMainWallet,
                            (float) $transaction->cost_price,
                            'wholesale_charge',
                            "Manual Settlement: Wholesale cost for delivered {$transaction->service_type} ({$transaction->recipient})",
                            ['transaction_id' => $transaction->id, 'reference' => $transaction->reference],
                            $rdbStoreRef,
                            $forceAllowNegative
                        );
                    }
                }
            }

            // 3. Allocate store profit if not yet swept
            $profitMargin = (float) $transaction->profit;
            $swpRef = 'SWP_'.$transaction->reference;
            $alreadySwept = WalletTransaction::where('reference', $swpRef)->exists();

            if (! $alreadySwept && $profitMargin > 0 && $store) {
                try {
                    $storeMainWallet = $store->mainWallet();
                    $storeProfitWallet = $store->profitWallet();

                    if ($storeMainWallet && $storeProfitWallet) {
                        $this->walletService->debit(
                            $storeMainWallet,
                            $profitMargin,
                            'profit_sweep',
                            "Profit Allocation: {$transaction->service_type} ({$transaction->recipient})",
                            [
                                'customer_id' => $transaction->user_id,
                                'amount_paid' => $transaction->amount_paid,
                                'cost_price' => $transaction->cost_price,
                                'profit_margin' => $profitMargin,
                            ],
                            $swpRef,
                            $forceAllowNegative
                        );

                        $this->walletService->credit(
                            $storeProfitWallet,
                            $profitMargin,
                            'earned_profit',
                            "Earned Profit: {$transaction->service_type} ({$transaction->recipient})",
                            [
                                'customer_id' => $transaction->user_id,
                                'amount_paid' => $transaction->amount_paid,
                                'cost_price' => $transaction->cost_price,
                                'profit_margin' => $profitMargin,
                            ],
                            'PRF_'.$transaction->reference
                        );
                    }
                } catch (\Throwable $profitErr) {
                    Log::error("Failed to sweep profit on manual mark as delivered for {$transaction->reference}: ".$profitErr->getMessage());
                }
            }

            // 4. Update transaction status
            $existingApiResponse = is_array($transaction->api_response) ? $transaction->api_response : [];
            $existingApiResponse['manual_settlement_at'] = now()->toIso8601String();
            $existingApiResponse['manual_settlement_by'] = auth()->id() ?? 'Admin';

            $transaction->update([
                'status' => 'successful',
                'api_response' => $existingApiResponse,
            ]);

            return [
                'success' => true,
                'message' => 'Transaction successfully marked as delivered. Customer and store debited, profit allocated.',
            ];
        });
    }
}
