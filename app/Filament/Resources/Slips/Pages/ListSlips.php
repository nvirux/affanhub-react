<?php

namespace App\Filament\Resources\Slips\Pages;

use App\Filament\Resources\Slips\SlipResource;
use App\Models\Service;
use App\Models\Slip;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSlips extends ListRecords
{
    protected static string $resource = SlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All Slips')
                ->badge(Slip::count()),
        ];

        $ninService = Service::where('key', 'nin_verification')->first();
        if ($ninService) {
            $tabs['nin'] = Tab::make('NIN Slips')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('service_id', $ninService->id))
                ->badge(Slip::where('service_id', $ninService->id)->count())
                ->badgeColor('success');
        }

        $bvnService = Service::where('key', 'bvn_verification')->first();
        if ($bvnService) {
            $tabs['bvn'] = Tab::make('BVN Slips')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('service_id', $bvnService->id))
                ->badge(Slip::where('service_id', $bvnService->id)->count())
                ->badgeColor('info');
        }

        return $tabs;
    }
}
