<?php

namespace App\Filament\Merchant\Resources\CustomerResource\Pages;

use App\Filament\Merchant\Resources\CustomerResource;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Customer'),
        ];
    }

    public function getTabs(): array
    {
        $store = Filament::getTenant();
        if (! $store) {
            return [];
        }

        return [
            'all' => Tab::make('All Customers')
                ->badge($store->users()->count()),

            'funded' => Tab::make('Funded (> ₦0)')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0)))
                ->badge($store->users()->whereHas('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0))->count())
                ->badgeColor('success'),

            'zero_balance' => Tab::make('Zero Balance')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0)))
                ->badge($store->users()->whereDoesntHave('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0))->count())
                ->badgeColor('gray'),

            'verified' => Tab::make('KYC Verified')
                ->modifyQueryUsing(fn (Builder $query) => $query->where(fn ($q) => $q->whereNotNull('bvn')->where('bvn', '!=', '')->orWhereNotNull('nin')->where('nin', '!=', '')))
                ->badge($store->users()->where(fn ($q) => $q->whereNotNull('bvn')->where('bvn', '!=', '')->orWhereNotNull('nin')->where('nin', '!=', ''))->count())
                ->badgeColor('info'),

            'suspended' => Tab::make('Suspended')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false))
                ->badge($store->users()->where('is_active', false)->count())
                ->badgeColor('danger'),
        ];
    }
}
