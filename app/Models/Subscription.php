<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'store_id',
        'plan_id',
        'price',
        'billing_interval',
        'status',
        'starts_at',
        'ends_at',
        'trial_ends_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if the subscription is currently active or in valid trial mode.
     */
    public function isActive(): bool
    {
        if ($this->status === 'cancelled' || $this->status === 'expired') {
            return false;
        }

        if ($this->status === 'trialing') {
            return $this->trial_ends_at && $this->trial_ends_at->isFuture();
        }

        return $this->ends_at ? $this->ends_at->isFuture() : true;
    }
}
