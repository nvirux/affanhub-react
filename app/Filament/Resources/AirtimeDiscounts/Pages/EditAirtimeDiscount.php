<?php

namespace App\Filament\Resources\AirtimeDiscounts\Pages;

use App\Filament\Resources\AirtimeDiscounts\AirtimeDiscountResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAirtimeDiscount extends EditRecord
{
    protected static string $resource = AirtimeDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
