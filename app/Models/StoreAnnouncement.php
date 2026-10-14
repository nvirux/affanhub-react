<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreAnnouncement extends Model
{
    protected $fillable = [
        'store_id',
        'title',
        'message',
        'type',
        'display_style',
        'action_label',
        'action_url',
        'is_active',
        'is_dismissible',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_dismissible' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * Scope a query to only include active and currently running announcements.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get the currently active announcement for a specific store.
     */
    public static function getActiveForStore(?Store $store): ?self
    {
        if (! $store) {
            return null;
        }

        return static::where('store_id', $store->id)
            ->active()
            ->latest('id')
            ->first();
    }
}
