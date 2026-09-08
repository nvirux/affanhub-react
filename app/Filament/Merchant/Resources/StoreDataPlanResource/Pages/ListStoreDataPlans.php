<?php

namespace App\Filament\Merchant\Resources\StoreDataPlanResource\Pages;

use App\Filament\Merchant\Resources\StoreDataPlanResource;
use App\Models\DataPlan;
use App\Models\Network;
use App\Models\StoreDataPlan;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStoreDataPlans extends ListRecords
{
    protected static string $resource = StoreDataPlanResource::class;

    protected function getHeaderActions(): array
    {
        return []; // Hide "Create" button since all master plans are auto-populated
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if ($store) {
            $masterPlans = DataPlan::where('is_active', true)->get();
            $existingPlanIds = StoreDataPlan::where('store_id', $store->id)
                ->pluck('data_plan_id')
                ->toArray();

            foreach ($masterPlans as $masterPlan) {
                if (! in_array($masterPlan->id, $existingPlanIds, true)) {
                    StoreDataPlan::create([
                        'store_id' => $store->id,
                        'data_plan_id' => $masterPlan->id,
                        'selling_price' => $masterPlan->default_retail_price,
                        'is_enabled' => true,
                        'is_best_offer' => (bool) $masterPlan->is_best_offer,
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
            'all' => Tab::make('All Networks')
                ->badge(StoreDataPlan::where('store_id', $storeId)->count()),
        ];

        $networks = Network::where('is_active', true)->get();

        foreach ($networks as $network) {
            $netId = $network->id;
            $tabs['net_'.$netId] = Tab::make($network->name)
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('dataPlan', fn ($q) => $q->where('network_id', $netId)))
                ->badge(
                    StoreDataPlan::where('store_id', $storeId)
                        ->whereHas('dataPlan', fn ($q) => $q->where('network_id', $netId))
                        ->count()
                );
        }

        return $tabs;
    }
}
