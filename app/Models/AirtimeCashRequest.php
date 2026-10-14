<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AirtimeCashRequest extends Model
{
    protected $fillable = [
        'reference',
        'store_id',
        'user_id',
        'network_id',
        'airtime_amount',
        'exchange_rate',
        'cash_amount',
        'sender_phone',
        'receiving_phone',
        'payout_method',
        'bank_name',
        'account_number',
        'account_name',
        'proof_image',
        'status',
        'admin_notes',
        'rejection_reason',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'airtime_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:2',
        'cash_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected $appends = [
        'proof_image_url',
    ];

    public function getProofImageUrlAttribute(): ?string
    {
        if (! $this->proof_image) {
            return null;
        }

        return asset('storage/'.$this->proof_image);
    }

    public static function generateReference(): string
    {
        return 'ATC-'.strtoupper(Str::random(10));
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve the airtime to cash request.
     * If payout_method is 'wallet', deposits cash directly into the customer's store wallet.
     */
    public function approve(?string $notes = null): void
    {
        if (! $this->isPending()) {
            throw new \RuntimeException('Only pending requests can be approved.');
        }

        DB::transaction(function () use ($notes) {
            if ($this->payout_method === 'wallet') {
                $description = 'Airtime to Cash: ₦'.number_format((float) $this->airtime_amount, 2)." ({$this->network?->name})";
                $this->user->wallet('main')->deposit(
                    amount: (float) $this->cash_amount,
                    description: $description,
                    reference: $this->reference,
                    meta: [
                        'type' => 'airtime_to_cash',
                        'network' => $this->network?->name,
                        'airtime_amount' => (float) $this->airtime_amount,
                        'rate' => (float) $this->exchange_rate,
                        'sender_phone' => $this->sender_phone,
                    ]
                );
            }

            // Record storefront transaction history for customer receipt and merchant profit tracking
            Transaction::updateOrCreate(
                ['reference' => $this->reference],
                [
                    'user_id' => $this->user_id,
                    'store_id' => $this->store_id,
                    'service_type' => 'airtime_to_cash',
                    'service_id' => null,
                    'amount' => (float) $this->airtime_amount,
                    'discount' => 0.00,
                    'amount_paid' => (float) $this->cash_amount,
                    'cost_price' => (float) $this->cash_amount,
                    'vendor_cost' => 0.00,
                    'profit' => (float) ($this->airtime_amount - $this->cash_amount),
                    'platform_profit' => 0.00,
                    'recipient' => $this->payout_method === 'bank'
                        ? "{$this->bank_name} • {$this->account_number} ({$this->account_name})"
                        : "Store Wallet ({$this->sender_phone})",
                    'status' => 'successful',
                    'api_response' => [
                        'type' => 'airtime_to_cash',
                        'network' => $this->network?->name,
                        'rate' => (float) $this->exchange_rate,
                        'sender_phone' => $this->sender_phone,
                        'receiving_phone' => $this->receiving_phone,
                        'payout_method' => $this->payout_method,
                        'admin_notes' => $notes,
                    ],
                ]
            );

            $this->update([
                'status' => 'approved',
                'admin_notes' => $notes,
                'approved_at' => now(),
            ]);
        });
    }

    /**
     * Reject the airtime to cash request with a reason.
     */
    public function reject(string $reason, ?string $notes = null): void
    {
        if (! $this->isPending()) {
            throw new \RuntimeException('Only pending requests can be rejected.');
        }

        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'admin_notes' => $notes,
            'rejected_at' => now(),
        ]);
    }
}
