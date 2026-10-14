<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'promo_until' => 'datetime',
        'trial_days' => 'integer',
    ];

    /**
     * Check if this service is currently in a promotional free window.
     */
    public function isInPromo(): bool
    {
        return $this->promo_until !== null && now()->lt($this->promo_until);
    }

    /**
     * Check if a specific store has access to this service.
     */
    public function isAccessibleByStore(?Store $store): bool
    {
        if (! $this->is_active) {
            return false;
        }

        // If no feature gating is required, everyone can access it
        if (! $this->feature_id || ! $this->feature) {
            return true;
        }

        if (! $store) {
            return true;
        }

        // 1. Promotional global free window
        if ($this->isInPromo()) {
            return true;
        }

        // 2. New store trial window
        if ($this->trial_days && $store->created_at && $store->created_at->addDays($this->trial_days)->isFuture()) {
            return true;
        }

        // 3. Regular Plan Entitlement
        try {
            return (bool) $store->hasFeature($this->feature->slug);
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Get IDs of plans that have access to this service's feature.
     */
    public function getAllowedPlanIds(): array
    {
        if (! $this->feature_id) {
            return Plan::pluck('id')->toArray();
        }

        return PlanFeature::where('feature_id', $this->feature_id)
            ->where(function ($query) {
                $query->where('value', 'true')
                    ->orWhere('value', '1');
            })
            ->pluck('plan_id')
            ->toArray();
    }

    /**
     * Get a human-readable summary of plans that can access this service.
     */
    public function getAllowedPlansSummary(): string
    {
        if (! $this->feature_id || ! $this->feature) {
            return 'Free (All Plans)';
        }

        $planIds = $this->getAllowedPlanIds();
        if (empty($planIds)) {
            return 'None / Locked';
        }

        $allPlansCount = Plan::count();
        if (count($planIds) >= $allPlansCount) {
            return 'All Plans';
        }

        $planNames = Plan::whereIn('id', $planIds)->pluck('name')->toArray();

        return implode(', ', $planNames);
    }

    /**
     * Get the existing linked feature or automatically find/create one.
     */
    public function getOrCreateFeature(): Feature
    {
        if ($this->feature) {
            return $this->feature;
        }

        $slug = match ($this->category) {
            'identity' => 'id_'.$this->key,
            default => 'vtu_'.$this->key,
        };

        $feature = Feature::where('slug', $slug)
            ->orWhere('name', 'like', "%{$this->name}%")
            ->first();

        if (! $feature) {
            $feature = Feature::create([
                'name' => "{$this->name} Access",
                'slug' => $slug,
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => "Access to {$this->name} service",
            ]);
        }

        $this->update(['feature_id' => $feature->id]);

        return $feature;
    }

    /**
     * Sync which plans have access to this service's feature.
     */
    public function syncPlansAccess(array $planIds): void
    {
        $feature = $this->getOrCreateFeature();

        $allPlans = Plan::all();
        foreach ($allPlans as $plan) {
            $isAllowed = in_array((int) $plan->id, array_map('intval', $planIds), true);
            PlanFeature::updateOrCreate(
                [
                    'plan_id' => $plan->id,
                    'feature_id' => $feature->id,
                ],
                [
                    'value' => $isAllowed ? 'true' : 'false',
                ]
            );
        }
    }

    /**
     * Sync this service into StoreService for all stores.
     */
    public function syncToAllStores(): int
    {
        $stores = Store::all();
        $count = 0;

        foreach ($stores as $store) {
            $hasAccess = $this->isAccessibleByStore($store);
            $storeService = StoreService::firstOrNew([
                'store_id' => $store->id,
                'service_id' => $this->id,
            ]);

            if (! $storeService->exists) {
                $storeService->is_enabled = $hasAccess;
                $storeService->sort_order = $this->sort_order ?? 0;
                $storeService->save();
                $count++;
            } elseif (! $this->is_active && $storeService->is_enabled) {
                $storeService->update(['is_enabled' => false]);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get the required plan for a store to access this service.
     */
    public function getRequiredPlanForStore(?Store $store): string
    {
        if (! $this->feature || ! $store) {
            return 'Free / All Plans';
        }

        try {
            return method_exists($store, 'getFeatureUpgradeRequirement')
                ? $store->getFeatureUpgradeRequirement($this->feature->slug)
                : 'Pro or Enterprise Plan';
        } catch (\Throwable) {
            return 'Pro or Enterprise Plan';
        }
    }

    /**
     * Sync all services across all stores.
     */
    public static function syncAllToStores(): int
    {
        $services = static::all();
        $total = 0;

        foreach ($services as $service) {
            $total += $service->syncToAllStores();
        }

        return $total;
    }

    /**
     * Get the feature linked to this service for plan gating.
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    /**
     * Get the official slips attached to this identity service.
     */
    public function slips(): HasMany
    {
        return $this->hasMany(Slip::class)->orderBy('sort_order');
    }
}
