<?php

namespace App\Filament\Merchant\Resources\CustomerResource\Pages;

use App\Filament\Merchant\Resources\CustomerResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
