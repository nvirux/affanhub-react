<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Customer'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Customers')
                ->badge(User::count()),

            'funded' => Tab::make('Funded (> ₦0)')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0)))
                ->badge(User::whereHas('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0))->count())
                ->badgeColor('success'),

            'zero_balance' => Tab::make('Zero Balance')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0)))
                ->badge(User::whereDoesntHave('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0))->count())
                ->badgeColor('gray'),

            'verified' => Tab::make('KYC Verified')
                ->modifyQueryUsing(fn (Builder $query) => $query->where(fn ($q) => $q->whereNotNull('bvn')->where('bvn', '!=', '')->orWhereNotNull('nin')->where('nin', '!=', '')))
                ->badge(User::where(fn ($q) => $q->whereNotNull('bvn')->where('bvn', '!=', '')->orWhereNotNull('nin')->where('nin', '!=', ''))->count())
                ->badgeColor('info'),

            'suspended' => Tab::make('Suspended')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false))
                ->badge(User::where('is_active', false)->count())
                ->badgeColor('danger'),
        ];
    }
}
