<?php

namespace App\Filament\Merchant\Resources\StoreServiceResource\Pages;

use App\Filament\Merchant\Resources\StoreServiceResource;
use App\Models\Service;
use App\Models\StoreService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStoreServices extends ListRecords
{
    protected static string $resource = StoreServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview_storefront')
                ->label('🌐 Preview Storefront')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn () => Filament::getTenant()?->getStoreUrl() ?? '/')
                ->openUrlInNewTab(),

            Action::make('enable_all_available')
                ->label('⚡ Enable All Available')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Enable All Available Services')
                ->modalDescription('This will turn on all services that are included in your current subscription plan across your customer storefront.')
                ->modalSubmitActionLabel('Enable All')
                ->action(function () {
                    $store = Filament::getTenant();
                    if (! $store) {
                        return;
                    }

                    $count = 0;
                    $storeServices = StoreService::where('store_id', $store->id)->with('service.feature')->get();
                    foreach ($storeServices as $storeService) {
                        if ($storeService->hasAccess() && ! $storeService->is_enabled) {
                            $storeService->update(['is_enabled' => true]);
                            $count++;
                        }
                    }

                    Notification::make()
                        ->title("{$count} services activated on your storefront!")
                        ->success()
                        ->send();
                }),

            Action::make('disable_all')
                ->label('Hide All')
                ->icon('heroicon-o-eye-slash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hide All Services from Storefront')
                ->modalDescription('This will temporarily hide all services from your customer storefront until you turn them back on.')
                ->modalSubmitActionLabel('Hide All')
                ->action(function () {
                    $store = Filament::getTenant();
                    if (! $store) {
                        return;
                    }

                    $count = StoreService::where('store_id', $store->id)
                        ->where('is_enabled', true)
                        ->update(['is_enabled' => false]);

                    Notification::make()
                        ->title("{$count} services hidden from your storefront.")
                        ->warning()
                        ->send();
                }),
        ];
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
                $hasAccess = $service->isAccessibleByStore($store);

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

            'active' => Tab::make('Active on Store')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_enabled', true))
                ->badge(
                    StoreService::where('store_id', $storeId)
                        ->where('is_enabled', true)
                        ->count()
                )
                ->badgeColor('success'),

            'vtu' => Tab::make('VTU Utilities')
                ->icon('heroicon-o-bolt')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('service', fn ($q) => $q->where('category', 'vtu')))
                ->badge(
                    StoreService::where('store_id', $storeId)
                        ->whereHas('service', fn ($q) => $q->where('category', 'vtu'))
                        ->count()
                ),

            'identity' => Tab::make('Identity Services')
                ->icon('heroicon-o-identification')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('service', fn ($q) => $q->where('category', 'identity')))
                ->badge(
                    StoreService::where('store_id', $storeId)
                        ->whereHas('service', fn ($q) => $q->where('category', 'identity'))
                        ->count()
                ),
        ];
    }
}
