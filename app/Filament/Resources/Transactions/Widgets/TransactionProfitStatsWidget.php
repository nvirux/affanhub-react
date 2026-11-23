<?php

namespace App\Filament\Resources\Transactions\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TransactionProfitStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonthStart = Carbon::now()->startOfMonth();

        $todayProfit = (float) Transaction::whereIn('status', ['successful', 'success', 'completed'])
            ->whereDate('created_at', $today)
            ->sum('platform_profit');

        $thisMonthProfit = (float) Transaction::whereIn('status', ['successful', 'success', 'completed'])
            ->where('created_at', '>=', $thisMonthStart)
            ->sum('platform_profit');

        $allTimeProfit = (float) Transaction::whereIn('status', ['successful', 'success', 'completed'])
            ->sum('platform_profit');

        $totalSuccessfulCount = Transaction::whereIn('status', ['successful', 'success', 'completed'])->count();

        return [
            Stat::make("Today's Platform Profit", '₦'.number_format($todayProfit, 2))
                ->description('Earned today from orders')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make("This Month's Platform Profit", '₦'.number_format($thisMonthProfit, 2))
                ->description('Earned this month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('All-Time Platform Profit', '₦'.number_format($allTimeProfit, 2))
                ->description("Across {$totalSuccessfulCount} successful orders")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}

