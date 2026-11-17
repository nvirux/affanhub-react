<?php

namespace App\Models;

use App\Services\OneSignalService;
use App\Services\WalletService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CacRequest extends Model
{
    protected $table = 'cac_requests';

    protected $fillable = [
        'reference',
        'store_id',
        'user_id',
        'store_cac_service_id',
        'service_name',
        'business_name',
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

    public function storeCacService(): BelongsTo
    {
        return $this->belongsTo(StoreCacService::class, 'store_cac_service_id');
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
        return 'CAC_'.strtoupper(Str::random(10));
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
            title: 'CAC Registration In Progress',
            message: "Your {$this->service_name} application ({$this->reference}) is currently being processed with CAC."
        );
    }

    /**
     * Complete the CAC order, attach result certificate, and settle merchant profit.
     */
    public function complete(?string $resultFilePath = null, ?string $notes = null): void
    {
        if ($this->isCompleted()) {
            return;
        }

        DB::transaction(function () use ($resultFilePath, $notes) {
            $store = $this->store;
            $profitWallet = $store?->profitWallet();
            $amount = (float) $this->amount_paid;

            // Settle merchant profit: Credit Profit Wallet directly from escrowed customer payment
            if ($this->settlement_type === 'online_profit_credit' && $profitWallet && $amount > 0) {
                $walletService = app(WalletService::class);

                $walletService->credit(
                    $profitWallet,
                    $amount,
                    'cac_profit',
                    "Profit from {$this->service_name} order #{$this->reference}",
                    ['order_reference' => $this->reference, 'customer_id' => $this->user_id],
                    'PRF_'.$this->reference
                );
            }

            $this->update([
                'status' => 'completed',
                'result_file_path' => $resultFilePath ?? $this->result_file_path,
                'admin_notes' => $notes ?? $this->admin_notes,
                'processed_at' => now(),
            ]);
        });

        $this->notifyCustomer(
            title: 'CAC Registration Completed! 🎉',
            message: "Your {$this->service_name} application ({$this->reference}) has been approved and completed. Your official certificate is ready."
        );
    }

    /**
     * Reject the CAC order and refund the customer wallet.
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
                    "Refund for rejected CAC application ({$this->service_name} #{$this->reference}): {$reason}",
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
            title: 'CAC Application Update',
            message: "Your {$this->service_name} application ({$this->reference}) was rejected. Reason: {$reason}. Funds have been refunded to your wallet."
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
                    actionUrl: '/cac'
                );
            }
        } catch (\Throwable $e) {
            Log::warning("Could not send CAC push notification: {$e->getMessage()}");
        }
    }
}
