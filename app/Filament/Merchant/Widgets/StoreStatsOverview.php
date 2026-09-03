<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $store = Filament::getTenant();

        if (!$store) {
            return [];
        }

        $mainBalance = $store->mainWallet()->balance;
        $profitBalance = $store->profitWallet()->balance;
        $customerCount = $store->users()->count();
        $staffCount = $store->members()->count();

        return [
            Stat::make('Main Wallet Balance', '₦' . number_format($mainBalance, 2))
                ->description('Operating balance for store funding')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('primary'),

            Stat::make('Profit Wallet Balance', '₦' . number_format($profitBalance, 2))
                ->description('Accumulated profit margin earnings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Registered Customers', number_format($customerCount))
                ->description('End-users registered on your store')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Staff Members', number_format($staffCount))
                ->description('Active store admin accounts')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
