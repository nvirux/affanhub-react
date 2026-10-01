<?php

namespace App\Filament\Merchant\Resources\StoreServiceResource\Pages;

use App\Filament\Merchant\Resources\StoreServiceResource;
use App\Models\Service;
use App\Models\StoreService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStoreServices extends ListRecords
{
    protected static string $resource = StoreServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if ($store) {
            $services = Service::where('is_active', true)->get();
            $existingServiceIds = StoreService::where('store_id', $store->id)
                ->pluck('service_id')
                ->toArray();

            foreach ($services as $service) {
                $hasAccess = true;
                if ($service->feature_id && $service->feature) {
                    try {
                        $hasAccess = (bool) $store->hasFeature($service->feature->slug);
                    } catch (\Throwable) {
                        $hasAccess = false;
                    }
                }

                if (! in_array($service->id, $existingServiceIds, true)) {
                    StoreService::create([
                        'store_id' => $store->id,
                        'service_id' => $service->id,
                        'is_enabled' => $hasAccess,
                        'sort_order' => $service->sort_order,
                    ]);
                } elseif (! $hasAccess) {
                    StoreService::where('store_id', $store->id)
                        ->where('service_id', $service->id)
                        ->where('is_enabled', true)
                        ->update(['is_enabled' => false]);
                }
            }
        }

        return parent::getTableQuery();
    }

    public function getTabs(): array
    {
        $store = Filament::getTenant();
        $storeId = $store ? $store->id : null;

        return [
            'all' => Tab::make('All Services')
                ->badge(
                    StoreService::where('store_id', $storeId)->count()
                ),

            'vtu' => Tab::make('VTU Utilities')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('service', fn ($q) => $q->where('category', 'vtu')))
                ->badge(
                    StoreService::where('store_id', $storeId)
                        ->whereHas('service', fn ($q) => $q->where('category', 'vtu'))
                        ->count()
                ),

            'identity' => Tab::make('Identity Services')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('service', fn ($q) => $q->where('category', 'identity')))
                ->badge(
                    StoreService::where('store_id', $storeId)
                        ->whereHas('service', fn ($q) => $q->where('category', 'identity'))
                        ->count()
                ),
        ];
    }
}
