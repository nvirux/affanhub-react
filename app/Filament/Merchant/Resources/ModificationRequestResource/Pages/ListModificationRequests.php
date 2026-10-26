<?php

namespace App\Filament\Merchant\Resources\ModificationRequestResource\Pages;

use App\Filament\Merchant\Resources\ModificationRequestResource;
use App\Filament\Merchant\Widgets\ModificationOverviewWidget;
use App\Models\ModificationRequest;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListModificationRequests extends ListRecords
{
    protected static string $resource = ModificationRequestResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ModificationOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        $store = Filament::getTenant();
        $storeId = $store ? $store->id : null;

        return [
            'all' => Tab::make('All Orders')
                ->badge(ModificationRequest::where('store_id', $storeId)->count()),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(ModificationRequest::where('store_id', $storeId)->where('status', 'pending')->count())
                ->badgeColor('warning'),

            'processing' => Tab::make('In Progress')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'processing'))
                ->badge(ModificationRequest::where('store_id', $storeId)->where('status', 'processing')->count())
                ->badgeColor('info'),

            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed'))
                ->badge(ModificationRequest::where('store_id', $storeId)->where('status', 'completed')->count())
                ->badgeColor('success'),

            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
                ->badge(ModificationRequest::where('store_id', $storeId)->where('status', 'rejected')->count())
                ->badgeColor('danger'),
        ];
    }
}
