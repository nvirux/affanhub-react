<?php

namespace App\Filament\Resources\StoreMobileApps\Pages;

use App\Filament\Resources\StoreMobileApps\StoreMobileAppResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStoreMobileApp extends EditRecord
{
    protected static string $resource = StoreMobileAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
