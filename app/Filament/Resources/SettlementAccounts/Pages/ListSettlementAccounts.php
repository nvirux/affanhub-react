<?php

namespace App\Filament\Resources\SettlementAccounts\Pages;

use App\Filament\Resources\SettlementAccounts\SettlementAccountResource;
use App\Models\SettlementAccount;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSettlementAccounts extends ListRecords
{
    protected static string $resource = SettlementAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Banks')
                ->badge(SettlementAccount::count()),
            'pending' => Tab::make('Pending Approval')
                ->badge(SettlementAccount::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),
            'approved' => Tab::make('Approved')
                ->badge(SettlementAccount::where('status', 'approved')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved')),
            'rejected' => Tab::make('Rejected')
                ->badge(SettlementAccount::where('status', 'rejected')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected')),
        ];
    }
}
