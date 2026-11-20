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
        if (! $this->service) {
            return 'Enterprise Plan or Contact Support';
        }

        return $this->service->getRequiredPlanForStore($tenant);
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
                'label' => 'Free Promo Active',
                'color' => 'warning',
                'description' => 'Unlocked free until '.$this->service->promo_until->format('M d, Y'),
                'is_locked' => false,
            ];
        }

        if ($this->service->trial_days && $tenant?->created_at && $tenant->created_at->addDays($this->service->trial_days)->isFuture()) {
            $daysLeft = (int) now()->diffInDays($tenant->created_at->addDays($this->service->trial_days));

            return [
                'label' => "{$this->service->trial_days}d New Store Trial",
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
            'label' => 'Upgrade Required',
            'color' => 'danger',
            'description' => $this->getRequiredPlan(),
            'is_locked' => true,
        ];
    }

    /**
     * Resolve the active icon key for storefront display (custom or default).
     */
    public function getResolvedIcon(): string
    {
        if ($this->icon && $this->icon !== 'default') {
            return $this->icon;
        }

        if ($this->icon === 'default') {
            return $this->service?->icon ?? 'Smartphone';
        }

        $key = $this->service?->key ?? '';
        if (str_contains($key, 'cac')) {
            return 'cac';
        }
        if (str_contains($key, 'nin')) {
            return 'nimc';
        }
        if (str_contains($key, 'bvn')) {
            return 'nibss';
        }

        return $this->service?->icon ?? 'Smartphone';
    }

    /**
     * Get strict permitted icon options for this service.
     */
    public function getAvailableIconOptions(): array
    {
        $key = $this->service?->key ?? '';

        return match ($key) {
            'bvn_verification' => [
                'nibss' => 'NIBSS (Official NIBSS Logo)',
                'verification' => 'Verification (Official Shield Badge)',
                'default' => 'Default (System Vector Icon)',
            ],
            'nin_verification' => [
                'nimc' => 'NIMC (Official NIMC Logo)',
                'verification' => 'Verification (Official Shield Badge)',
                'default' => 'Default (System Vector Icon)',
            ],
            'cac_services' => [
                'cac' => 'CAC (Official CAC Logo)',
                'default' => 'Default (System Vector Icon)',
            ],
            'nin_modification', 'nin_validation', 'ipe_clearance' => [
                'nimc' => 'NIMC (Official NIMC Logo)',
                'default' => 'Default (System Vector Icon)',
            ],
            'bvn_modification' => [
                'nibss' => 'NIBSS (Official NIBSS Logo)',
                'default' => 'Default (System Vector Icon)',
            ],
            default => [],
        };
    }
}
