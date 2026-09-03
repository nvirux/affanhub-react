<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreQuotaWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();
        
        // 1. Plan Stats
        $subscription = $tenant->activeSubscription;
        $planName = $subscription?->plan?->name ?? 'Starter';
        $interval = $subscription ? ucfirst($subscription->billing_interval) : 'Free';
        $renewal = $subscription?->ends_at ? 'Renews ' . $subscription->ends_at->format('M d, Y') : 'Free Forever';

        // 2. Staff Stats
        $staffLimit = $tenant->getFeatureLimit('staff_limit');
        $currentStaff = $tenant->members()->count();
        $staffPercent = $staffLimit > 0 ? round(($currentStaff / $staffLimit) * 100) : 0;
        
        $staffColor = 'success';
        if ($staffPercent >= 100) {
            $staffColor = 'danger';
        } elseif ($staffPercent >= 80) {
            $staffColor = 'warning';
        }

        // 3. Domain Stats
        $hasCustomDomain = $tenant->hasFeature('custom_domain');
        $primaryDomain = $tenant->domains()->where('is_primary', true)->first();
        
        $domainStatus = 'Default';
        $domainDesc = $primaryDomain?->domain ?? 'No domains linked';
        $domainColor = 'gray';

        if ($hasCustomDomain) {
            $customDomains = $tenant->domains()->get()->filter(fn ($d) => $d->isCustom());
            if ($customDomains->isEmpty()) {
                $domainStatus = 'Unlocked';
                $domainDesc = 'Ready to connect custom domain';
                $domainColor = 'info';
            } else {
                $verifiedCount = $customDomains->filter(fn ($d) => $d->isHealthy())->count();
                $domainStatus = $verifiedCount . ' Active';
                $domainDesc = $primaryDomain?->domain ?? 'Custom domain active';
                $domainColor = 'success';
            }
        } else {
            $domainStatus = 'Locked';
            $domainDesc = 'Upgrade to unlock custom domains';
            $domainColor = 'warning';
        }

        // 4. Customers Count
        $customersCount = $tenant->users()->count();

        return [
            Stat::make('Current Plan', $planName)
                ->description("{$interval} Plan — {$renewal}")
                ->descriptionIcon($subscription ? 'heroicon-m-sparkles' : 'heroicon-m-shield-check')
                ->color($subscription ? 'success' : 'gray'),

            Stat::make('Staff Accounts', "{$currentStaff} / {$staffLimit}")
                ->description("{$staffPercent}% utilization of limit")
                ->descriptionIcon('heroicon-m-users')
                ->color($staffColor),

            Stat::make('Custom Domain', $domainStatus)
                ->description($domainDesc)
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color($domainColor),

            Stat::make('Total Customers', number_format($customersCount))
                ->description('Active store shoppers')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
        ];
    }
}
