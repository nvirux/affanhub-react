<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slip extends Model
{
    use HasFactory;

    protected $table = 'slips';

    protected $fillable = [
        'service_id',
        'slug',
        'name',
        'badge',
        'description',
        'features',
        'color',
        'is_popular',
        'cost_price',
        'selling_price',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function storeSlips(): HasMany
    {
        return $this->hasMany(StoreSlip::class);
    }

    public function identityVerifications(): HasMany
    {
        return $this->hasMany(IdentityVerification::class);
    }

    /**
     * Get the active retail price for a specific store.
     */
    public function getStorePrice(int $storeId): float
    {
        $storeSlip = $this->storeSlips()->where('store_id', $storeId)->first();

        return (float) ($storeSlip?->selling_price ?? $this->selling_price);
    }

    /**
     * Check if this slip format is enabled for a specific store.
     */
    public function isEnabledForStore(int $storeId): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $storeSlip = $this->storeSlips()->where('store_id', $storeId)->first();

        return $storeSlip ? (bool) $storeSlip->is_enabled : true;
    }
}
