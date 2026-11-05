<?php

namespace App\Filament\Resources\Stores\Pages;

use App\Filament\Resources\Stores\StoreResource;
use App\Models\Store;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStores extends ListRecords
{
    protected static string $resource = StoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Stores')
                ->badge(Store::count()),

            'active' => Tab::make('Active Stores')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'active'))
                ->badge(Store::where('status', 'active')->count())
                ->badgeColor('success'),

            'suspended' => Tab::make('Suspended')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'suspended'))
                ->badge(Store::where('status', 'suspended')->count())
                ->badgeColor('danger'),

            'unfunded' => Tab::make('Unfunded Stores')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0)))
                ->badge(Store::whereDoesntHave('wallets', fn ($q) => $q->where('type', 'main')->where('balance', '>', 0))->count())
                ->badgeColor('warning'),
        ];
    }
}
