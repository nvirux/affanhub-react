<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $table = 'plans';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'trial_days',
        'is_active',
        'is_self_serve',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'trial_days' => 'integer',
        'is_active' => 'boolean',
        'is_self_serve' => 'boolean',
    ];

    public function isSelfServe(): bool
    {
        return (bool) ($this->is_self_serve ?? true)
            && $this->slug !== 'enterprise'
            && ($this->slug === 'starter' || ($this->price_monthly !== null && (float) $this->price_monthly > 0));
    }

    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function planAirtimeDiscounts(): HasMany
    {
        return $this->hasMany(PlanAirtimeDiscount::class);
    }

    public function getFeatureValue(string $featureSlug)
    {
        $feature = Feature::where('slug', $featureSlug)->first();
        if (! $feature) {
            return null;
        }

        $planFeature = $this->planFeatures()
            ->where('feature_id', $feature->id)
            ->first();

        $value = $planFeature ? $planFeature->value : $feature->default_value;

        if (is_null($value)) {
            return null;
        }

        switch ($feature->type) {
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
