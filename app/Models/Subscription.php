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
        'grace_ends_at',
        'reminder_3days_sent_at',
        'reminder_1day_sent_at',
        'grace_notified_at',
        'downgraded_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'grace_ends_at' => 'datetime',
        'reminder_3days_sent_at' => 'datetime',
        'reminder_1day_sent_at' => 'datetime',
        'grace_notified_at' => 'datetime',
        'downgraded_at' => 'datetime',
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
     * Check if the subscription is currently active, trialing, or in valid grace period.
     */
    public function isActive(): bool
    {
        if ($this->status === 'cancelled' || $this->status === 'expired') {
            return false;
        }

        if ($this->status === 'past_due') {
            return $this->grace_ends_at ? $this->grace_ends_at->isFuture() : false;
        }

        if ($this->status === 'trialing') {
            return $this->trial_ends_at && $this->trial_ends_at->isFuture();
        }

        return $this->ends_at ? $this->ends_at->isFuture() : true;
    }

    /**
     * Check if this is a free default plan (e.g. Starter).
     */
    public function isFree(): bool
    {
        return (float) $this->price <= 0 || ($this->plan && $this->plan->slug === 'starter');
    }

    /**
     * Check if the subscription is currently within its 3-day grace period.
     */
    public function isInGracePeriod(): bool
    {
        return $this->status === 'past_due' && $this->grace_ends_at && $this->grace_ends_at->isFuture();
    }

    /**
     * Check if the subscription will expire within the given number of days.
     */
    public function isExpiringSoon(int $days = 3): bool
    {
        if ($this->isFree() || $this->status !== 'active' || ! $this->ends_at || ! $this->ends_at->isFuture()) {
            return false;
        }

        $remainingDays = $this->daysUntilExpiration();

        return $remainingDays > 0 && $remainingDays <= $days;
    }

    /**
     * Remaining days until subscription ends.
     */
    public function daysUntilExpiration(): int
    {
        if (! $this->ends_at || ! $this->ends_at->isFuture()) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($this->ends_at) / 86400);
    }

    /**
     * Remaining days in grace period.
     */
    public function daysUntilGraceEnd(): int
    {
        if (! $this->grace_ends_at || ! $this->grace_ends_at->isFuture()) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($this->grace_ends_at) / 86400);
    }
}
