<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Transaction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Transactions')
                ->badge(Transaction::count()),

            'successful' => Tab::make('Successful')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['successful', 'success', 'completed']))
                ->badge(
                    Transaction::whereIn('status', ['successful', 'success', 'completed'])->count()
                )
                ->badgeColor('success'),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['pending', 'processing']))
                ->badge(
                    Transaction::whereIn('status', ['pending', 'processing'])->count()
                )
                ->badgeColor('warning'),

            'failed' => Tab::make('Failed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'failed'))
                ->badge(
                    Transaction::where('status', 'failed')->count()
                )
                ->badgeColor('danger'),

            'refunded' => Tab::make('Refunded')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'refunded'))
                ->badge(
                    Transaction::where('status', 'refunded')->count()
                )
                ->badgeColor('gray'),
        ];
    }
}
