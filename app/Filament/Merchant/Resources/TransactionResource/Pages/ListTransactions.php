<?php

namespace App\Filament\Merchant\Resources\TransactionResource\Pages;

use App\Filament\Merchant\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $store = Filament::getTenant();
        $storeId = $store ? $store->id : null;

        return [
            'all' => Tab::make('All Transactions')
                ->badge(Transaction::where('store_id', $storeId)->count()),

            'successful' => Tab::make('Successful')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['successful', 'success', 'completed']))
                ->badge(
                    Transaction::where('store_id', $storeId)
                        ->whereIn('status', ['successful', 'success', 'completed'])
                        ->count()
                )
                ->badgeColor('success'),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['pending', 'processing']))
                ->badge(
                    Transaction::where('store_id', $storeId)
                        ->whereIn('status', ['pending', 'processing'])
                        ->count()
                )
                ->badgeColor('warning'),

            'failed' => Tab::make('Failed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'failed'))
                ->badge(
                    Transaction::where('store_id', $storeId)
                        ->where('status', 'failed')
                        ->count()
                )
                ->badgeColor('danger'),

            'refunded' => Tab::make('Refunded')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'refunded'))
                ->badge(
                    Transaction::where('store_id', $storeId)
                        ->where('status', 'refunded')
                        ->count()
                )
                ->badgeColor('gray'),
        ];
    }
}
