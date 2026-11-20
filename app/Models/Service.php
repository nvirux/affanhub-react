<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * Get the plans that have direct access to this service.
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_service')->withTimestamps();
    }

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

        // 3. Direct Plan-Service Entitlement
        $allowedPlanIds = $this->getAllowedPlanIds();
        if (empty($allowedPlanIds)) {
            // If neither plans nor features are configured, allow access by default
            return ! $this->feature_id;
        }

        $activeSub = $store->activeSubscription;
        $currentPlan = ($activeSub && $activeSub->isActive())
            ? $activeSub->plan
            : Plan::where('slug', 'starter')->first();

        if (! $currentPlan) {
            return false;
        }

        return in_array((int) $currentPlan->id, array_map('intval', $allowedPlanIds), true);
    }

    /**
     * Get IDs of plans that have access to this service.
     */
    public function getAllowedPlanIds(): array
    {
        $directIds = $this->plans()->pluck('plans.id')->toArray();
        if (! empty($directIds)) {
            return $directIds;
        }

        // Fallback to feature_id for backward compatibility
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
        $planIds = $this->getAllowedPlanIds();
        if (empty($planIds)) {
            return 'None / Locked';
        }

        $allPlansCount = Plan::count();
        if ($allPlansCount > 0 && count($planIds) >= $allPlansCount) {
            return 'All Plans';
        }

        $planNames = Plan::whereIn('id', $planIds)->pluck('name')->toArray();

        return ! empty($planNames) ? implode(', ', $planNames) : 'None / Locked';
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
     * Sync which plans have access to this service.
     */
    public function syncPlansAccess(array $planIds): void
    {
        $this->plans()->sync($planIds);

        // Also sync to legacy feature if present for backward compatibility
        if ($this->feature_id) {
            $allPlans = Plan::all();
            foreach ($allPlans as $plan) {
                $isAllowed = in_array((int) $plan->id, array_map('intval', $planIds), true);
                PlanFeature::updateOrCreate(
                    [
                        'plan_id' => $plan->id,
                        'feature_id' => $this->feature_id,
                    ],
                    [
                        'value' => $isAllowed ? 'true' : 'false',
                    ]
                );
            }
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
        $allowedPlanIds = $this->getAllowedPlanIds();
        if (empty($allowedPlanIds)) {
            return 'Enterprise Plan or Contact Support';
        }

        $allowedPlans = Plan::whereIn('id', $allowedPlanIds)->get()->keyBy('slug');
        if ($allowedPlans->has('starter')) {
            return 'Starter Plan';
        }

        if ($allowedPlans->has('pro')) {
            return 'Pro Plan';
        }

        if ($allowedPlans->has('enterprise')) {
            return 'Enterprise Plan';
        }

        $first = $allowedPlans->first();
        if ($first) {
            return "{$first->name} Plan";
        }

        return 'Enterprise Plan or Contact Support';
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
