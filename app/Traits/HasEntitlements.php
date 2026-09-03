<?php

namespace App\Traits;

use App\Models\Feature;
use App\Models\PlanFeature;
use App\Models\StoreFeatureOverride;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasEntitlements
{
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'store_id');
    }

    /**
     * Get the store's current active or trialing subscription.
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'store_id')
            ->where(function ($query) {
                $query->where('status', 'active')
                    ->orWhere('status', 'trialing');
            })
            ->latestOfMany();
    }

    public function featureOverrides(): HasMany
    {
        return $this->hasMany(StoreFeatureOverride::class, 'store_id');
    }

    /**
     * Check if store has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        $sub = $this->activeSubscription;
        return $sub ? $sub->isActive() : false;
    }

    /**
     * Check if the store is currently suspended (expired/past_due).
     */
    public function isSuspended(): bool
    {
        return !$this->hasActiveSubscription();
    }

    /**
     * Get the resolved value of a feature capability.
     */
    public function getFeatureValue(string $featureSlug)
    {
        // 1. Resolve feature metadata
        $feature = Feature::where('slug', $featureSlug)->first();
        if (!$feature) {
            return null;
        }

        // 2. Resolve Store Specific Override (highest priority)
        $override = $this->featureOverrides()
            ->where('feature_id', $feature->id)
            ->first();

        if ($override) {
            return $this->castFeatureValue($override->value, $feature->type);
        }

        // 3. Resolve Subscription Plan default feature value
        $activeSub = $this->activeSubscription;
        if ($activeSub && $activeSub->isActive()) {
            $planFeature = PlanFeature::where('plan_id', $activeSub->plan_id)
                ->where('feature_id', $feature->id)
                ->first();

            if ($planFeature) {
                return $this->castFeatureValue($planFeature->value, $feature->type);
            }
        }

        // 4. Resolve global system default (lowest priority fallback)
        return $this->castFeatureValue($feature->default_value, $feature->type);
    }

    /**
     * Resolves and returns a boolean value for checking permissions/access.
     */
    public function hasFeature(string $featureSlug): bool
    {
        return (bool) $this->getFeatureValue($featureSlug);
    }

    /**
     * Resolves and returns an integer value for checking numerical limits.
     */
    public function getFeatureLimit(string $featureSlug): int
    {
        return (int) $this->getFeatureValue($featureSlug);
    }

    /**
     * Converts a database string value to its typed scalar representation.
     */
    protected function castFeatureValue($value, string $type)
    {
        if (is_null($value)) {
            return null;
        }

        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'decimal':
                return (float) $value;
            default:
                return (string) $value;
            }
    }
}
