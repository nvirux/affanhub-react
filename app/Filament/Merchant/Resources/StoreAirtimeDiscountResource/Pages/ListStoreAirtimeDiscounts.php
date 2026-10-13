<?php

namespace App\Filament\Merchant\Resources\StoreAirtimeDiscountResource\Pages;

use App\Filament\Merchant\Resources\StoreAirtimeDiscountResource;
use App\Models\Network;
use App\Models\StoreAirtimeDiscount;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListStoreAirtimeDiscounts extends ListRecords
{
    protected static string $resource = StoreAirtimeDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('wholesale_rates')
                ->label('View Wholesale Rates')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn () => route('filament.merchant.pages.wholesale-rates', [
                    'tenant' => Filament::getTenant()->public_id,
                    'tab' => 'airtime',
                ])),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $store = Filament::getTenant();

        if ($store) {
            $networks = Network::where('is_active', true)->get();
            $existingNetworkIds = StoreAirtimeDiscount::where('store_id', $store->id)
                ->pluck('network_id')
                ->toArray();

            foreach ($networks as $network) {
                if (! in_array($network->id, $existingNetworkIds, true)) {
                    StoreAirtimeDiscount::create([
                        'store_id' => $store->id,
                        'network_id' => $network->id,
                        'selling_discount' => null, // null means use global network default
                        'min_amount' => null,
                        'max_amount' => null,
                        'is_enabled' => true,
                    ]);
                }
            }
        }

        return parent::getTableQuery();
    }
}
