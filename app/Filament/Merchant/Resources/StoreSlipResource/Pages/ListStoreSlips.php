<?php

namespace App\Filament\Merchant\Resources\StoreSlipResource\Pages;

use App\Filament\Merchant\Resources\StoreSlipResource;
use App\Models\Service;
use App\Models\Slip;
use App\Models\StoreSlip;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStoreSlips extends ListRecords
{
    protected static string $resource = StoreSlipResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if ($store) {
            $masterSlips = Slip::where('is_active', true)->get();
            $existingSlipIds = StoreSlip::where('store_id', $store->id)
                ->pluck('slip_id')
                ->toArray();

            foreach ($masterSlips as $masterSlip) {
                if (! in_array($masterSlip->id, $existingSlipIds, true)) {
                    StoreSlip::create([
                        'store_id' => $store->id,
                        'slip_id' => $masterSlip->id,
                        'selling_price' => $masterSlip->selling_price,
                        'is_enabled' => true,
                    ]);
                }
            }
        }

        return parent::getTableQuery();
    }

    public function getTabs(): array
    {
        $store = Filament::getTenant();
        $storeId = $store ? $store->id : null;

        $tabs = [
            'all' => Tab::make('All Slips')
                ->badge(StoreSlip::where('store_id', $storeId)->count()),
        ];

        $ninService = Service::where('key', 'nin_verification')->first();
        if ($ninService) {
            $tabs['nin'] = Tab::make('NIN Slips')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('slip', fn ($q) => $q->where('service_id', $ninService->id)))
                ->badge(
                    StoreSlip::where('store_id', $storeId)
                        ->whereHas('slip', fn ($q) => $q->where('service_id', $ninService->id))
                        ->count()
                )
                ->badgeColor('success');
        }

        $bvnService = Service::where('key', 'bvn_verification')->first();
        if ($bvnService) {
            $tabs['bvn'] = Tab::make('BVN Slips')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('slip', fn ($q) => $q->where('service_id', $bvnService->id)))
                ->badge(
                    StoreSlip::where('store_id', $storeId)
                        ->whereHas('slip', fn ($q) => $q->where('service_id', $bvnService->id))
                        ->count()
                )
                ->badgeColor('info');
        }

        return $tabs;
    }
}
