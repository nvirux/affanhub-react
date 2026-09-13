<?php

namespace App\Filament\Merchant\Widgets;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $store = Filament::getTenant();

        if (! $store) {
            return [];
        }

        $mainBalance = (float) ($store->mainWallet()->balance ?? 0.00);
        $profitBalance = (float) ($store->profitWallet()->balance ?? 0.00);

        // Calculate aggregate wallet balance across all customers registered under this store
        $customerWalletBalance = (float) Wallet::where('holder_type', User::class)
            ->whereIn('holder_id', $store->users()->select('id'))
            ->sum('balance');

        $customerCount = $store->users()->count();
        $newCustomersThisWeek = $store->users()->where('created_at', '>=', now()->subDays(7))->count();

        // 30-day and today's sales volume
        $salesToday = (float) Transaction::where('store_id', $store->id)
            ->where('status', 'success')
            ->whereDate('created_at', today())
            ->sum('amount_paid');

        $sales30Days = (float) Transaction::where('store_id', $store->id)
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('amount_paid');

        $totalProfitEarned = (float) Transaction::where('store_id', $store->id)
            ->where('status', 'success')
            ->sum('profit');

        // Liquidity coverage calculation
        $isSolvent = $mainBalance >= $customerWalletBalance;
        $coverageText = $customerWalletBalance > 0
            ? round(($mainBalance / $customerWalletBalance) * 100).'% float coverage'
            : 'Fully covered (0 liabilities)';

        return [
            Stat::make('Main Operating Capital', '₦'.number_format($mainBalance, 2))
                ->description('Operating balance for store orders')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('primary'),

            Stat::make('Profit Wallet Balance', '₦'.number_format($profitBalance, 2))
                ->description('Total earned: ₦'.number_format($totalProfitEarned, 2))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Users Wallet Balance', '₦'.number_format($customerWalletBalance, 2))
                ->description($isSolvent ? "Healthy ({$coverageText})" : 'Low Capital: user balances exceed main capital')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color($isSolvent ? 'info' : 'danger'),

            Stat::make('30-Day Sales Volume', '₦'.number_format($sales30Days, 2))
                ->description('Today: ₦'.number_format($salesToday, 2))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Registered Customers', number_format($customerCount))
                ->description("+{$newCustomersThisWeek} new this week")
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
