<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use App\Models\Service;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync_all_services')
                ->label('🔄 Sync All to Stores')
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Synchronize All Services to Stores')
                ->modalDescription('This will inspect all active services and ensure every store has a StoreService entry with the correct access level.')
                ->action(function (): void {
                    $total = Service::syncAllToStores();
                    Notification::make()
                        ->title('All Services Synchronized!')
                        ->body("{$total} store service records were created or refreshed.")
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Services')
                ->badge(Service::count()),
            'vtu' => Tab::make('VTU Utilities')
                ->icon('heroicon-o-bolt')
                ->badge(Service::where('category', 'vtu')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', 'vtu')),
            'identity' => Tab::make('Identity Services')
                ->icon('heroicon-o-identification')
                ->badge(Service::where('category', 'identity')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', 'identity')),
        ];
    }
}
