<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreService extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function hasAccess(): bool
    {
        $tenant = $this->store ?? Filament::getTenant();
        if (! $tenant || ! $this->service) {
            return true;
        }

        if ($this->service->feature_id && $this->service->feature) {
            try {
                return (bool) $tenant->hasFeature($this->service->feature->slug);
            } catch (\Throwable) {
                return false;
            }
        }

        return true;
    }

    public function getRequiredPlan(): string
    {
        $tenant = $this->store ?? Filament::getTenant();
        if (! $tenant || ! $this->service || ! $this->service->feature) {
            return 'Enterprise Plan or Contact Support';
        }

        try {
            return method_exists($tenant, 'getFeatureUpgradeRequirement')
                ? $tenant->getFeatureUpgradeRequirement($this->service->feature->slug)
                : 'Enterprise Plan';
        } catch (\Throwable) {
            return 'Enterprise Plan or Contact Support';
        }
    }
}
