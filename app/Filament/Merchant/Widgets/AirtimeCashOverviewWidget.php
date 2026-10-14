<?php

namespace App\Filament\Merchant\Widgets;

use App\Filament\Merchant\Resources\AirtimeCashRequestResource;
use App\Models\AirtimeCashRequest;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AirtimeCashOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $store = Filament::getTenant();

        if (! $store) {
            return [];
        }

        $storeId = $store->id;

        // 1. Pending conversions waiting for merchant action
        $pendingQuery = AirtimeCashRequest::where('store_id', $storeId)->where('status', 'pending');
        $pendingCount = $pendingQuery->count();
        $pendingAmount = (float) $pendingQuery->sum('cash_amount');

        // 2. Approved orders today
        $approvedTodayQuery = AirtimeCashRequest::where('store_id', $storeId)
            ->where('status', 'approved')
            ->whereDate('approved_at', today());
        $approvedTodayCount = $approvedTodayQuery->count();
        $convertedTodayAmount = (float) $approvedTodayQuery->sum('airtime_amount');
        $payoutTodayAmount = (float) $approvedTodayQuery->sum('cash_amount');
        $profitToday = (float) max(0, $convertedTodayAmount - $payoutTodayAmount);

        // 3. All-time completed stats
        $allTimeCompleted = AirtimeCashRequest::where('store_id', $storeId)
            ->where('status', 'approved')
            ->count();

        $indexUrl = AirtimeCashRequestResource::getUrl('index');

        return [
            Stat::make('Pending Conversions', (string) $pendingCount)
                ->description($pendingCount > 0 ? '₦'.number_format($pendingAmount, 2).' awaiting your payout' : 'All orders processed')
                ->descriptionIcon($pendingCount > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-badge')
                ->color($pendingCount > 0 ? 'warning' : 'gray')
                ->url($indexUrl),

            Stat::make('Airtime Received Today', '₦'.number_format($convertedTodayAmount, 2))
                ->description($approvedTodayCount > 0 ? $approvedTodayCount.' completed trades today' : 'No trades today yet')
                ->descriptionIcon('heroicon-m-arrow-path-rounded-square')
                ->color('success')
                ->url($indexUrl),

            Stat::make('Airtime Profit Today', '₦'.number_format($profitToday, 2))
                ->description($allTimeCompleted > 0 ? $allTimeCompleted.' total trades all-time' : 'From conversion fees')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->url($indexUrl),
        ];
    }
}
