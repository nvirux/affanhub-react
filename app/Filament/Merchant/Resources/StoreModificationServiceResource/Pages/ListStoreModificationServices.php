<?php

namespace App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages;

use App\Filament\Merchant\Resources\StoreModificationServiceResource;
use App\Models\Service;
use App\Models\StoreModificationService;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStoreModificationServices extends ListRecords
{
    protected static string $resource = StoreModificationServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Custom Modification')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if ($store) {
            $count = StoreModificationService::where('store_id', $store->id)->count();
            if ($count === 0) {
                StoreModificationService::seedDefaultsForStore($store);
            }
        }

        return parent::getTableQuery();
    }

    public function getTabs(): array
    {
        $store = Filament::getTenant();
        $storeId = $store ? $store->id : null;

        $tabs = [
            'all' => Tab::make('All Services')
                ->badge(StoreModificationService::where('store_id', $storeId)->count()),
        ];

        $ninService = Service::where('key', 'nin_modification')->first();
        if ($ninService) {
            $tabs['nin'] = Tab::make('NIN Services')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('service_id', $ninService->id))
                ->badge(
                    StoreModificationService::where('store_id', $storeId)
                        ->where('service_id', $ninService->id)
                        ->count()
                )
                ->badgeColor('success');
        }

        $bvnService = Service::where('key', 'bvn_modification')->first();
        if ($bvnService) {
            $tabs['bvn'] = Tab::make('BVN Services')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('service_id', $bvnService->id))
                ->badge(
                    StoreModificationService::where('store_id', $storeId)
                        ->where('service_id', $bvnService->id)
                        ->count()
                )
                ->badgeColor('info');
        }

        return $tabs;
    }
}
