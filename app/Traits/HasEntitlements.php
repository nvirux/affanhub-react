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
        return ! $this->hasActiveSubscription();
    }

    /**
     * Get the resolved value of a feature capability.
     */
    public function getFeatureValue(string $featureSlug)
    {
        // 1. Resolve feature metadata
        $feature = Feature::where('slug', $featureSlug)->first();
        if (! $feature) {
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
     * Alias for hasFeature to check entitlement access.
     */
    public function hasEntitlement(string $featureSlug): bool
    {
        return $this->hasFeature($featureSlug);
    }

    /**
     * Resolves and returns an integer value for checking numerical limits.
     */
    public function getFeatureLimit(string $featureSlug): int
    {
        return (int) $this->getFeatureValue($featureSlug);
    }

    /**
     * Get a human-readable upgrade or plan recommendation message for a locked feature.
     */
    public function getFeatureUpgradeRequirement(string $featureSlug): string
    {
        $feature = Feature::where('slug', $featureSlug)->first();
        if (! $feature) {
            return 'Enterprise Plan or Contact Support';
        }

        $activeSub = $this->activeSubscription;
        $currentPlanSlug = $activeSub?->plan?->slug ?? 'starter';

        // Find plans that enable this feature
        $plansWithFeature = PlanFeature::where('feature_id', $feature->id)
            ->where(function ($query) {
                $query->where('value', 'true')
                    ->orWhere('value', '1')
                    ->orWhereRaw('CAST(value AS INTEGER) > 0');
            })
            ->with('plan')
            ->get()
            ->sortBy(fn ($pf) => $pf->plan?->price_monthly ?? 999999);

        if ($plansWithFeature->isEmpty()) {
            return 'Enterprise Plan or Contact Support';
        }

        // Check if feature is in Enterprise only
        $hasPro = $plansWithFeature->contains(fn ($pf) => $pf->plan?->slug === 'pro');
        $hasEnterprise = $plansWithFeature->contains(fn ($pf) => $pf->plan?->slug === 'enterprise');

        if ($currentPlanSlug === 'pro') {
            if ($hasEnterprise) {
                return 'Enterprise Plan';
            }

            return 'Enterprise Plan or Contact Support';
        }

        if ($currentPlanSlug === 'starter') {
            if ($hasPro) {
                return 'Pro Plan';
            }
            if ($hasEnterprise) {
                return 'Enterprise Plan';
            }
        }

        return 'Enterprise Plan or Contact Support';
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
