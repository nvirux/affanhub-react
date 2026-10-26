<?php

namespace App\Filament\Merchant\Resources\StoreModificationServiceResource\Pages;

use App\Filament\Merchant\Resources\StoreModificationServiceResource;
use Filament\Resources\Pages\EditRecord;

class EditStoreModificationService extends EditRecord
{
    protected static string $resource = StoreModificationServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return "Configure {$this->record->name}";
    }
}
