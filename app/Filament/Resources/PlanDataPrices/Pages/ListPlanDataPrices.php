<?php

namespace App\Filament\Resources\PlanDataPrices\Pages;

use App\Filament\Resources\PlanDataPrices\PlanDataPriceResource;
use App\Models\Network;
use App\Models\PlanDataPrice;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPlanDataPrices extends ListRecords
{
    protected static string $resource = PlanDataPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All Networks')
                ->badge(PlanDataPrice::count()),
        ];

        $networks = Network::where('is_active', true)->get();

        foreach ($networks as $network) {
            $netId = $network->id;
            $tabs['net_' . $netId] = Tab::make($network->name)
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('dataPlan', fn ($q) => $q->where('network_id', $netId)))
                ->badge(
                    PlanDataPrice::whereHas('dataPlan', fn ($q) => $q->where('network_id', $netId))->count()
                );
        }

        return $tabs;
    }
}
