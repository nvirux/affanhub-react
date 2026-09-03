<?php

namespace App\Filament\Merchant\Resources\CustomerResource\Pages;

use App\Filament\Merchant\Resources\CustomerResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
