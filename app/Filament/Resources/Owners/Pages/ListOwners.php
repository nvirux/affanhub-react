<?php

namespace App\Filament\Resources\Owners\Pages;

use App\Filament\Resources\Owners\OwnerResource;
use App\Models\Owner;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOwners extends ListRecords
{
    protected static string $resource = OwnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Accounts')
                ->badge(Owner::count()),

            'owners' => Tab::make('Store Owners')
                ->modifyQueryUsing(fn (Builder $query) => $query->has('ownedStores'))
                ->badge(Owner::has('ownedStores')->count())
                ->badgeColor('success'),

            'staff' => Tab::make('Staff & Managers')
                ->modifyQueryUsing(fn (Builder $query) => $query->doesntHave('ownedStores'))
                ->badge(Owner::doesntHave('ownedStores')->count())
                ->badgeColor('info'),

            'needs_followup' => Tab::make('Needs Follow-Up')
                ->modifyQueryUsing(fn (Builder $query) => $query->doesntHave('ownedStores')->where('created_at', '<=', now()->subHours(12)))
                ->badge(Owner::doesntHave('ownedStores')->where('created_at', '<=', now()->subHours(12))->count())
                ->badgeColor('warning'),
        ];
    }
}
