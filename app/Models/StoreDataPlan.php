<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDataPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'data_plan_id',
        'selling_price',
        'is_enabled',
        'is_best_offer',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'is_enabled' => 'boolean',
        'is_best_offer' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function dataPlan()
    {
        return $this->belongsTo(DataPlan::class);
    }

    /**
     * Resolve the store's exact wholesale purchase cost based on their subscription tier or base selling price,
     * protected by the platform minimum profit floor.
     */
    public function getWholesaleCost(): float
    {
        $store = $this->store ?? ($this->store_id ? Store::find($this->store_id) : null);
        $tierSlug = 'starter';

        if ($store) {
            $subscription = Subscription::with('plan')->where('store_id', $store->id)
                ->whereIn('status', ['active', 'trialing'])
                ->latest()
                ->first();

            if ($subscription && $subscription->plan) {
                $tierSlug = strtolower((string) $subscription->plan->slug);
            }

            if ($subscription && $subscription->plan_id) {
                $planPrice = PlanDataPrice::where('plan_id', $subscription->plan_id)
                    ->where('data_plan_id', $this->data_plan_id)
                    ->first();

                if ($planPrice && $planPrice->wholesale_price !== null && (float) $planPrice->wholesale_price > 0) {
                    return $this->applyPlatformProfitFloor((float) $planPrice->wholesale_price, $tierSlug);
                }
            }
        }

        $plan = $this->dataPlan ?? ($this->data_plan_id ? DataPlan::find($this->data_plan_id) : null);
        $baseWholesale = (float) ($plan?->selling_price ?? $plan?->default_retail_price ?? 0.0);

        return $this->applyPlatformProfitFloor($baseWholesale, $tierSlug);
    }

    /**
     * Clamp wholesale cost to guarantee platform minimum profit floor.
     */
    public function applyPlatformProfitFloor(float $wholesalePrice, string $tierSlug = 'starter'): float
    {
        $plan = $this->dataPlan ?? ($this->data_plan_id ? DataPlan::find($this->data_plan_id) : null);
        $vendorCost = (float) ($plan?->cost_price ?? 0.0);
        if ($vendorCost <= 0) {
            return $wholesalePrice;
        }

        $minPlatformMargin = $plan
            ? $plan->getMinProfitFloor($tierSlug)
            : match ($tierSlug) {
                'enterprise' => (float) PlatformSetting::get('vtu_min_profit_enterprise', 5.00),
                'pro' => (float) PlatformSetting::get('vtu_min_profit_pro', 10.00),
                default => (float) PlatformSetting::get('vtu_min_profit_starter', 15.00),
            };

        $minFloor = (float) PlatformSetting::get('vtu_min_platform_profit_floor', 5.00);
        $minPlatformMargin = max($minPlatformMargin, $minFloor);

        $floorWholesale = round($vendorCost + $minPlatformMargin, 2);

        return max($wholesalePrice, $floorWholesale);
    }
}
