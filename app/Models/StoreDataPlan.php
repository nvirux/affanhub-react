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
     * Resolve the store's exact wholesale purchase cost based on their subscription tier or base selling price.
     */
    public function getWholesaleCost(): float
    {
        $store = $this->store;
        if ($store) {
            $subscription = Subscription::where('store_id', $store->id)
                ->whereIn('status', ['active', 'trialing'])
                ->latest()
                ->first();

            if ($subscription && $subscription->plan_id) {
                $planPrice = PlanDataPrice::where('plan_id', $subscription->plan_id)
                    ->where('data_plan_id', $this->data_plan_id)
                    ->first();

                if ($planPrice && $planPrice->wholesale_price !== null) {
                    return (float) $planPrice->wholesale_price;
                }
            }
        }

        return (float) ($this->dataPlan?->selling_price ?? $this->dataPlan?->default_retail_price ?? 0.0);
    }
}
