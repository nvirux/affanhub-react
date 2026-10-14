<?php

namespace App\Filament\Merchant\Resources\StoreAnnouncementResource\Pages;

use App\Filament\Merchant\Resources\StoreAnnouncementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStoreAnnouncements extends ListRecords
{
    protected static string $resource = StoreAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Announcement')
                ->icon('heroicon-o-plus')
                ->modalHeading('Create Customer Announcement')
                ->modalWidth('2xl'),
        ];
    }
}
