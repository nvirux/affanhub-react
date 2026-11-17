<?php

namespace App\Filament\Merchant\Resources\StoreCacServiceResource\Pages;

use App\Filament\Merchant\Resources\StoreCacServiceResource;
use App\Models\StoreCacService;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListStoreCacServices extends ListRecords
{
    protected static string $resource = StoreCacServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New CAC Package')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if ($store) {
            $count = StoreCacService::where('store_id', $store->id)->count();
            if ($count === 0) {
                StoreCacService::seedDefaultsForStore($store);
            }
        }

        return parent::getTableQuery();
    }
}
