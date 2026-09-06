<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\WalletTransactionResource\Pages\ListWalletTransactions;
use App\Models\Store;
use App\Models\User;
use App\Models\WalletTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use UnitEnum;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Store Management';

    protected static ?string $navigationLabel = 'Financial Statements';

    protected static ?string $modelLabel = 'Financial Statement';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->sortable()
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
                        if (!$wallet) return 'N/A';

                        if ($wallet->holder_type === Store::class) {
                            return 'STORE ' . strtoupper($wallet->type ?? 'MAIN');
                        }

                        if ($wallet->holder_type === User::class) {
                            $user = User::find($wallet->holder_id);
                            return 'CUST: ' . ($user->name ?? 'User #' . $wallet->holder_id);
                        }

                        return 'ACCOUNT #' . $wallet->id;
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
                    ->label('Amount (₦)')
                    ->money('NGN')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('balance_before')
                    ->label('Balance Before (₦)')
                    ->money('NGN')
                    ->color('gray'),
                TextColumn::make('balance_after')
                    ->label('Balance After (₦)')
                    ->money('NGN')
                    ->color('gray'),
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'credit' => 'Credit (+)',
                        'debit' => 'Debit (-)',
                    ]),
                SelectFilter::make('category')
                    ->options([
                        'bank_transfer_deposit' => 'Customer Bank Deposit',
                        'store_auto_credit' => 'Store Wholesale Auto Pass-through',
                        'purchase' => 'Customer Retail Purchase',
                        'wholesale_cost' => 'Store Wholesale Cost',
                        'profit_earned' => 'Store Profit Earned',
                        'manual_merchant_credit' => 'Manual Merchant Credit',
                        'manual_merchant_debit' => 'Manual Merchant Debit',
                        'manual_admin_credit' => 'Admin Capital Credit',
                        'manual_admin_debit' => 'Admin Capital Debit',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWalletTransactions::route('/'),
        ];
    }
}
