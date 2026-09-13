<?php

namespace App\Filament\Merchant\Widgets;

use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentWalletTransactionsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $store = Filament::getTenant();

        if (! $store) {
            $query = WalletTransaction::query()->whereRaw('1 = 0');
        } else {
            $storeWalletIds = Wallet::where('holder_type', Store::class)
                ->where('holder_id', $store->id)
                ->pluck('id')
                ->toArray();

            $customerUserIds = $store->users()->pluck('users.id')->toArray();

            $customerWalletIds = Wallet::where('holder_type', User::class)
                ->whereIn('holder_id', $customerUserIds)
                ->pluck('id')
                ->toArray();

            $allWalletIds = array_unique(array_merge($storeWalletIds, $customerWalletIds));

            $query = WalletTransaction::query()
                ->whereIn('wallet_id', $allWalletIds)
                ->with(['wallet'])
                ->latest();
        }

        return $table
            ->query($query)
            ->heading('Recent Wallet & Ledger Statements')
            ->description('Financial activity across your store operating capital, profit wallet, and customer deposits')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('wallet_owner')
                    ->label('Wallet Account')
                    ->badge()
                    ->color(fn (WalletTransaction $record) => match ($record->wallet->holder_type ?? '') {
                        Store::class => ($record->wallet->type === 'profit' ? 'success' : 'info'),
                        User::class => 'primary',
                        default => 'gray',
                    })
                    ->state(function (WalletTransaction $record) {
                        $wallet = $record->wallet;
                        if (! $wallet) {
                            return 'N/A';
                        }

                        if ($wallet->holder_type === Store::class) {
                            return 'STORE '.strtoupper($wallet->type ?? 'MAIN');
                        }

                        if ($wallet->holder_type === User::class) {
                            $user = User::find($wallet->holder_id);

                            return 'CUST: '.($user->name ?? 'User #'.$wallet->holder_id);
                        }

                        return 'ACCOUNT #'.$wallet->id;
                    }),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state) => $state === 'credit' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color('warning')
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('NGN')
                    ->weight('bold'),

                TextColumn::make('balance_after')
                    ->label('Balance After')
                    ->money('NGN')
                    ->color('gray'),

                TextColumn::make('description')
                    ->label('Description')
                    ->wrap()
                    ->limit(50),
            ])
            ->emptyStateHeading('No wallet transactions yet')
            ->emptyStateDescription('Financial ledger events and wallet fundings will appear here.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }
}
