<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PlatformProfitOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // 1. Successful Transaction Platform Profit
        $todayTxProfit = (float) Transaction::whereIn('status', ['successful', 'success', 'completed'])
            ->whereDate('created_at', $today)
            ->sum('platform_profit');

        $thisMonthTxProfit = (float) Transaction::whereIn('status', ['successful', 'success', 'completed'])
            ->where('created_at', '>=', $thisMonthStart)
            ->sum('platform_profit');

        $lastMonthTxProfit = (float) Transaction::whereIn('status', ['successful', 'success', 'completed'])
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('platform_profit');

        $allTimeTxProfit = (float) Transaction::whereIn('status', ['successful', 'success', 'completed'])
            ->sum('platform_profit');

        // 2. Active Subscription Revenue (SaaS Revenue)
        $todaySubRevenue = (float) Subscription::where('status', 'active')
            ->whereDate('created_at', $today)
            ->sum('price');

        $thisMonthSubRevenue = (float) Subscription::where('status', 'active')
            ->where('created_at', '>=', $thisMonthStart)
            ->sum('price');

        $lastMonthSubRevenue = (float) Subscription::where('status', 'active')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('price');

        $allTimeSubRevenue = (float) Subscription::where('status', 'active')
            ->sum('price');

        // Combined Platform Profit
        $todayTotalProfit = $todayTxProfit + $todaySubRevenue;
        $thisMonthTotalProfit = $thisMonthTxProfit + $thisMonthSubRevenue;
        $lastMonthTotalProfit = $lastMonthTxProfit + $lastMonthSubRevenue;
        $allTimeTotalProfit = $allTimeTxProfit + $allTimeSubRevenue;

        // Total Vending Sales Volume This Month
        $thisMonthSalesVolume = (float) Transaction::whereIn('status', ['successful', 'success', 'completed'])
            ->where('created_at', '>=', $thisMonthStart)
            ->sum('amount');

        // 7-day sparkline history for Today's stat card
        $sparkline = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $dayProfit = (float) Transaction::whereIn('status', ['successful', 'success', 'completed'])
                ->whereDate('created_at', $day)
                ->sum('platform_profit');
            $sparkline[] = round($dayProfit, 2);
        }

        // Percentage comparison for This Month vs Last Month
        $growthLabel = 'No previous month data';
        $growthIcon = 'heroicon-m-minus';
        $growthColor = 'gray';

        if ($lastMonthTotalProfit > 0) {
            $percentageChange = (($thisMonthTotalProfit - $lastMonthTotalProfit) / $lastMonthTotalProfit) * 100;
            if ($percentageChange >= 0) {
                $growthLabel = '+'.number_format($percentageChange, 1).'% vs last month';
                $growthIcon = 'heroicon-m-arrow-trending-up';
                $growthColor = 'success';
            } else {
                $growthLabel = number_format($percentageChange, 1).'% vs last month';
                $growthIcon = 'heroicon-m-arrow-trending-down';
                $growthColor = 'danger';
            }
        } elseif ($thisMonthTotalProfit > 0) {
            $growthLabel = '100% new revenue vs last month';
            $growthIcon = 'heroicon-m-arrow-trending-up';
            $growthColor = 'success';
        }

        return [
            Stat::make("Today's Profit", '₦'.number_format($todayTotalProfit, 2))
                ->description('₦'.number_format($todayTxProfit, 2).' vending + ₦'.number_format($todaySubRevenue, 2).' subs')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart($sparkline)
                ->color('success'),

            Stat::make("This Month's Profit", '₦'.number_format($thisMonthTotalProfit, 2))
                ->description($growthLabel)
                ->descriptionIcon($growthIcon)
                ->color($growthColor),

            Stat::make('All-Time Net Profit', '₦'.number_format($allTimeTotalProfit, 2))
                ->description('Total cumulative earnings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make("This Month's Volume", '₦'.number_format($thisMonthSalesVolume, 2))
                ->description('Total vending volume processed')
                ->descriptionIcon('heroicon-m-arrow-path-rounded-square')
                ->color('info'),
        ];
    }
}

