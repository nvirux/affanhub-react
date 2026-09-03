<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Services'),
            'vtu' => Tab::make('VTU Utilities')
                ->icon('heroicon-o-bolt')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', 'vtu')),
            'identity' => Tab::make('Identity Services')
                ->icon('heroicon-o-identification')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', 'identity')),
        ];
    }
}
