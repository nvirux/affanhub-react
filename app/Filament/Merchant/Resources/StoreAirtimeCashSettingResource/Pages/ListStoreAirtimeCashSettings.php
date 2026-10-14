<?php

namespace App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource\Pages;

use App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource;
use App\Filament\Merchant\Widgets\AirtimeCashPromoBannerWidget;
use App\Models\Network;
use App\Models\StoreAirtimeCashSetting;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListStoreAirtimeCashSettings extends ListRecords
{
    protected static string $resource = StoreAirtimeCashSettingResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            AirtimeCashPromoBannerWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_requests')
                ->label('View Conversion Orders')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->url(fn () => route('filament.merchant.resources.airtime-cash-requests.index', [
                    'tenant' => Filament::getTenant()->public_id,
                ])),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if ($store) {
            $networks = Network::where('is_active', true)->get();
            $existingNetworkIds = StoreAirtimeCashSetting::where('store_id', $store->id)
                ->pluck('network_id')
                ->toArray();

            foreach ($networks as $network) {
                if (! in_array($network->id, $existingNetworkIds, true)) {
                    StoreAirtimeCashSetting::create([
                        'store_id' => $store->id,
                        'network_id' => $network->id,
                        'receiving_phone' => null,
                        'exchange_rate' => 80.00,
                        'min_amount' => 500.00,
                        'max_amount' => 50000.00,
                        'is_enabled' => true,
                    ]);
                }
            }
        }

        return parent::getTableQuery();
    }
}
