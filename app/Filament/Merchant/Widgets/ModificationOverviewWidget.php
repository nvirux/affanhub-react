<?php

namespace App\Filament\Merchant\Widgets;

use App\Filament\Merchant\Resources\ModificationRequestResource;
use App\Models\ModificationRequest;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ModificationOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $store = Filament::getTenant();

        if (! $store) {
            return [];
        }

        $storeId = $store->id;

        // 1. Pending modification requests awaiting merchant review
        $pendingQuery = ModificationRequest::where('store_id', $storeId)->where('status', 'pending');
        $pendingCount = $pendingQuery->count();
        $pendingAmount = (float) $pendingQuery->sum('amount_paid');

        // 2. In progress / processing orders
        $processingQuery = ModificationRequest::where('store_id', $storeId)->where('status', 'processing');
        $processingCount = $processingQuery->count();

        // 3. Completed orders today & all-time
        $completedTodayQuery = ModificationRequest::where('store_id', $storeId)
            ->where('status', 'completed')
            ->whereDate('processed_at', today());
        $completedTodayCount = $completedTodayQuery->count();
        $completedTodayRevenue = (float) $completedTodayQuery->sum('amount_paid');

        $allTimeCompleted = ModificationRequest::where('store_id', $storeId)
            ->where('status', 'completed')
            ->count();

        $indexUrl = ModificationRequestResource::getUrl('index');

        return [
            Stat::make('Pending Modifications', (string) $pendingCount)
                ->description($pendingCount > 0 ? '₦'.number_format($pendingAmount, 2).' awaiting document review' : 'All orders reviewed')
                ->descriptionIcon($pendingCount > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-badge')
                ->color($pendingCount > 0 ? 'warning' : 'gray')
                ->url($indexUrl),

            Stat::make('In Progress', (string) $processingCount)
                ->description($processingCount > 0 ? $processingCount.' active with NIMC/BVN' : 'No active batch')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color($processingCount > 0 ? 'info' : 'gray')
                ->url($indexUrl),

            Stat::make('Earned Today', '₦'.number_format($completedTodayRevenue, 2))
                ->description($completedTodayCount > 0 ? "{$completedTodayCount} finished today · {$allTimeCompleted} all-time" : ($allTimeCompleted > 0 ? "{$allTimeCompleted} completed all-time" : 'No completed orders yet'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->url($indexUrl),
        ];
    }
}
