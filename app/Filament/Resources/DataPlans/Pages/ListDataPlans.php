<?php

namespace App\Filament\Resources\DataPlans\Pages;

use App\Filament\Resources\DataPlans\DataPlanResource;
use App\Models\DataPlan;
use App\Models\Network;
use App\Services\Vtu\DataPlanSyncService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDataPlans extends ListRecords
{
    protected static string $resource = DataPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync_provider')
                ->label('Sync Plans from Provider')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Sync Data Plans from VTU Provider')
                ->modalDescription('This will query the provider API (GET /data/plans) to fetch and update all data plans, wholesale costs, validity, and availability. Continue?')
                ->modalSubmitActionLabel('Sync Now')
                ->action(function (DataPlanSyncService $syncService) {
                    $result = $syncService->sync();

                    if ($result['success']) {
                        Notification::make()
                            ->title('Sync Complete')
                            ->body($result['message'])
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Sync Failed')
                            ->body($result['message'])
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All Networks')
                ->badge(DataPlan::count()),
        ];

        $networks = Network::where('is_active', true)->get();

        foreach ($networks as $network) {
            $netId = $network->id;
            $tabs['net_'.$netId] = Tab::make($network->name)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('network_id', $netId))
                ->badge(DataPlan::where('network_id', $netId)->count());
        }

        return $tabs;
    }
}
