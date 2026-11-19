<?php

namespace App\Filament\Merchant\Resources\StoreCacServiceResource\Pages;

use App\Filament\Merchant\Resources\StoreCacServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStoreCacService extends EditRecord
{
    protected static string $resource = StoreCacServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
