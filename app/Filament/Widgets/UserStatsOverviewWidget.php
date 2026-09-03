<?php

namespace App\Filament\Widgets;

use App\Models\Admin;
use App\Models\Owner;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $adminCount = Admin::count();
        $ownerCount = Owner::count();
        $userCount = User::count();

        return [
            Stat::make('Platform Admins', $adminCount)
                ->description('System administrators')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('danger'),
            Stat::make('Merchants (Owners)', $ownerCount)
                ->description('Active store managers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
            Stat::make('Storefront Customers', $userCount)
                ->description('Total registered buyers')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
        ];
    }
}
