<?php

namespace App\Filament\Resources\AirtimeDiscounts\Pages;

use App\Filament\Resources\AirtimeDiscounts\AirtimeDiscountResource;
use App\Models\AirtimeDiscount;
use App\Models\Network;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAirtimeDiscounts extends ListRecords
{
    protected static string $resource = AirtimeDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        // Auto-seed default networks if missing
        $networks = Network::where('is_active', true)->get();
        $existingNetworkIds = AirtimeDiscount::pluck('network_id')->toArray();

        foreach ($networks as $network) {
            if (! in_array($network->id, $existingNetworkIds, true)) {
                AirtimeDiscount::create([
                    'network_id' => $network->id,
                    'buy_discount' => 3.50,
                    'default_merchant_discount' => 2.00,
                    'default_retail_discount' => 1.50,
                    'min_amount' => 50.00,
                    'max_amount' => 50000.00,
                    'is_active' => true,
                ]);
            }
        }

        return parent::getTableQuery();
    }
}
