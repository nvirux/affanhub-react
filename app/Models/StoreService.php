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

        return $this->service->isAccessibleByStore($tenant);
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

    /**
     * Get rich badge details for Merchant Services & Layout page.
     */
    public function getAccessBadgeDetails(): array
    {
        $tenant = $this->store ?? Filament::getTenant();

        if (! $this->service) {
            return [
                'label' => 'Available',
                'color' => 'success',
                'description' => null,
                'is_locked' => false,
            ];
        }

        if ($this->service->isInPromo()) {
            return [
                'label' => '🔥 Free Promo Active',
                'color' => 'warning',
                'description' => 'Unlocked free until '.$this->service->promo_until->format('M d, Y'),
                'is_locked' => false,
            ];
        }

        if ($this->service->trial_days && $tenant?->created_at && $tenant->created_at->addDays($this->service->trial_days)->isFuture()) {
            $daysLeft = (int) now()->diffInDays($tenant->created_at->addDays($this->service->trial_days));

            return [
                'label' => "⏱️ {$this->service->trial_days}d New Store Trial",
                'color' => 'info',
                'description' => "Trial active ({$daysLeft} days left)",
                'is_locked' => false,
            ];
        }

        if ($this->hasAccess()) {
            $planName = $tenant?->activeSubscription?->plan?->name ?? 'Active Plan';

            return [
                'label' => "Included in {$planName}",
                'color' => 'success',
                'description' => 'Unlocked by your subscription tier',
                'is_locked' => false,
            ];
        }

        return [
            'label' => '🔒 Upgrade Required',
            'color' => 'danger',
            'description' => $this->getRequiredPlan(),
            'is_locked' => true,
        ];
    }
}
