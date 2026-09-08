<?php

namespace App\Filament\Widgets;

use App\Models\Store;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SaaSOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $activeStores = Store::where('status', 'active')->count();
        $totalSubscriptions = Subscription::where('status', 'active')->count();
        $monthlyRevenue = Subscription::where('status', 'active')
            ->where('billing_interval', 'month')
            ->sum('price');
        $yearlyRevenue = Subscription::where('status', 'active')
            ->where('billing_interval', 'year')
            ->sum('price');

        $estimatedMonthlyValue = $monthlyRevenue + ($yearlyRevenue / 12);

        return [
            Stat::make('Active Stores', $activeStores)
                ->description('Total active merchant storefronts')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),
            Stat::make('Active Subscriptions', $totalSubscriptions)
                ->description('Currently paid or active plans')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('info'),
            Stat::make('Monthly Run Rate', '₦'.number_format($estimatedMonthlyValue, 2))
                ->description('Estimated MRR from subscriptions')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
