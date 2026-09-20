<?php

namespace App\Filament\Resources\StoreMobileApps\Pages;

use App\Filament\Resources\StoreMobileApps\StoreMobileAppResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStoreMobileApps extends ListRecords
{
    protected static string $resource = StoreMobileAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
