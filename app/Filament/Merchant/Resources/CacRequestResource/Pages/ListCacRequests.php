<?php

namespace App\Filament\Merchant\Resources\CacRequestResource\Pages;

use App\Filament\Merchant\Resources\CacRequestResource;
use App\Models\CacRequest;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCacRequests extends ListRecords
{
    protected static string $resource = CacRequestResource::class;

    public function getTabs(): array
    {
        $store = Filament::getTenant();
        $storeId = $store ? $store->id : null;

        return [
            'all' => Tab::make('All Orders')
                ->badge(CacRequest::where('store_id', $storeId)->count()),

            'pending' => Tab::make('Pending Review')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(CacRequest::where('store_id', $storeId)->where('status', 'pending')->count())
                ->badgeColor('warning'),

            'processing' => Tab::make('In Progress')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'processing'))
                ->badge(CacRequest::where('store_id', $storeId)->where('status', 'processing')->count())
                ->badgeColor('info'),

            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed'))
                ->badge(CacRequest::where('store_id', $storeId)->where('status', 'completed')->count())
                ->badgeColor('success'),

            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
                ->badge(CacRequest::where('store_id', $storeId)->where('status', 'rejected')->count())
                ->badgeColor('danger'),
        ];
    }
}
