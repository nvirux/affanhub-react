<?php

namespace App\Filament\Widgets;

use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialReservesWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $customerReserve = (float) Wallet::where('holder_type', User::class)
            ->where('type', 'main')
            ->sum('balance');

        $wholesaleReserve = (float) Wallet::where('holder_type', Store::class)
            ->where('type', 'main')
            ->sum('balance');

        $merchantProfits = (float) Wallet::where('holder_type', Store::class)
            ->where('type', 'profit')
            ->sum('balance');

        return [
            Stat::make('Customer Reserve', '₦'.number_format($customerReserve, 2))
                ->description('Total customer wallet balances')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Wholesale Capital Reserve', '₦'.number_format($wholesaleReserve, 2))
                ->description('Merchant wholesale capital for orders')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary'),
            Stat::make('Unclaimed Merchant Profits', '₦'.number_format($merchantProfits, 2))
                ->description('Merchant profits awaiting withdrawal')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
