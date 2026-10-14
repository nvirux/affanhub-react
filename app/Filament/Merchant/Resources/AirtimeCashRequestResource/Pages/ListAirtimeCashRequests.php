<?php

namespace App\Filament\Merchant\Resources\AirtimeCashRequestResource\Pages;

use App\Filament\Merchant\Resources\AirtimeCashRequestResource;
use App\Models\AirtimeCashRequest;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAirtimeCashRequests extends ListRecords
{
    protected static string $resource = AirtimeCashRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $store = Filament::getTenant();
        $storeId = $store ? $store->id : null;

        return [
            'pending' => Tab::make('Pending Review')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(
                    AirtimeCashRequest::where('store_id', $storeId)
                        ->where('status', 'pending')
                        ->count()
                )
                ->badgeColor('warning'),

            'approved' => Tab::make('Approved & Paid')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved'))
                ->badge(
                    AirtimeCashRequest::where('store_id', $storeId)
                        ->where('status', 'approved')
                        ->count()
                )
                ->badgeColor('success'),

            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
                ->badge(
                    AirtimeCashRequest::where('store_id', $storeId)
                        ->where('status', 'rejected')
                        ->count()
                )
                ->badgeColor('danger'),

            'all' => Tab::make('All Orders')
                ->badge(AirtimeCashRequest::where('store_id', $storeId)->count()),
        ];
    }
}
