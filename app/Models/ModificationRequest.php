<?php

namespace App\Models;

use App\Services\OneSignalService;
use App\Services\WalletService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ModificationRequest extends Model
{
    protected $table = 'modification_requests';

    protected $fillable = [
        'reference',
        'store_id',
        'user_id',
        'store_modification_service_id',
        'service_id',
        'service_name',
        'identifier',
        'old_data',
        'new_data',
        'submitted_fields',
        'uploaded_documents',
        'amount_paid',
        'status',
        'admin_notes',
        'rejection_reason',
        'result_file_path',
        'settlement_type',
        'processed_at',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'submitted_fields' => 'array',
        'uploaded_documents' => 'array',
        'amount_paid' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function storeModificationService(): BelongsTo
    {
        return $this->belongsTo(StoreModificationService::class, 'store_modification_service_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public static function generateReference(): string
    {
        return 'MOD_'.strtoupper(Str::random(10));
    }

    /**
     * Mark request as in-progress / processing.
     */
    public function markProcessing(?string $notes = null): void
    {
        $this->update([
            'status' => 'processing',
            'admin_notes' => $notes ?? $this->admin_notes,
        ]);

        $this->notifyCustomer(
            title: 'Modification In Progress',
            message: "Your {$this->service_name} request ({$this->reference}) is currently being processed."
        );
    }

    /**
     * Complete the modification order and settle merchant profit.
     */
    public function complete(?string $resultFilePath = null, ?string $notes = null): void
    {
        if ($this->isCompleted()) {
            return;
        }

        DB::transaction(function () use ($resultFilePath, $notes) {
            $store = $this->store;
            $storeMainWallet = $store?->mainWallet();
            $profitWallet = $store?->profitWallet();
            $amount = (float) $this->amount_paid;

            // Settle merchant profit: Move from Store Main Wallet to Profit Wallet
            if ($this->settlement_type === 'online_profit_credit' && $storeMainWallet && $profitWallet && $amount > 0) {
                $walletService = app(WalletService::class);

                // If store main wallet has sufficient funds, debit main and credit profit wallet
                if ((float) $storeMainWallet->balance >= $amount) {
                    $walletService->debit(
                        $storeMainWallet,
                        $amount,
                        'modification_settlement_out',
                        "Settlement transfer to profit for order #{$this->reference}",
                        ['order_reference' => $this->reference, 'service' => $this->service_name],
                        'SETTLE_'.$this->reference
                    );

                    $walletService->credit(
                        $profitWallet,
                        $amount,
                        'modification_profit',
                        "Profit from {$this->service_name} order #{$this->reference}",
                        ['order_reference' => $this->reference, 'customer_id' => $this->user_id],
                        'PRF_'.$this->reference
                    );
                }
            }

            $this->update([
                'status' => 'completed',
                'result_file_path' => $resultFilePath ?? $this->result_file_path,
                'admin_notes' => $notes ?? $this->admin_notes,
                'processed_at' => now(),
            ]);
        });

        $this->notifyCustomer(
            title: 'Modification Completed! 🎉',
            message: "Your {$this->service_name} request ({$this->reference}) has been completed successfully."
        );
    }

    /**
     * Reject the modification order and refund the customer wallet.
     */
    public function reject(string $reason, ?string $notes = null): void
    {
        if ($this->isRejected() || $this->isCompleted()) {
            return;
        }

        DB::transaction(function () use ($reason, $notes) {
            $user = $this->user;
            $customerWallet = $user?->wallet('main');
            $amount = (float) $this->amount_paid;

            if ($customerWallet && $amount > 0) {
                app(WalletService::class)->refund(
                    $customerWallet,
                    $amount,
                    "Refund for rejected {$this->service_name} (#{$this->reference}): {$reason}",
                    [
                        'order_reference' => $this->reference,
                        'rejection_reason' => $reason,
                        'service' => $this->service_name,
                    ],
                    'REF_'.$this->reference
                );
            }

            $this->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'admin_notes' => $notes ?? $this->admin_notes,
                'processed_at' => now(),
            ]);
        });

        $this->notifyCustomer(
            title: 'Modification Request Update',
            message: "Your {$this->service_name} request ({$this->reference}) was rejected. Reason: {$reason}. Funds have been refunded to your wallet."
        );
    }

    /**
     * Helper to send push notification to customer if configured.
     */
    protected function notifyCustomer(string $title, string $message): void
    {
        try {
            if ($this->user_id && $this->store_id) {
                app(OneSignalService::class)->sendToCustomer(
                    userId: $this->user_id,
                    storeId: $this->store_id,
                    title: $title,
                    message: $message,
                    actionUrl: "/identity/modification/{$this->reference}"
                );
            }
        } catch (\Throwable $e) {
            Log::warning("Could not send modification push notification: {$e->getMessage()}");
        }
    }
}
