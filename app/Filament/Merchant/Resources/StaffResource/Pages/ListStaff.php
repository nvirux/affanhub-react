<?php

namespace App\Filament\Merchant\Resources\StaffResource\Pages;

use App\Filament\Merchant\Resources\StaffResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListStaff extends ListRecords
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
