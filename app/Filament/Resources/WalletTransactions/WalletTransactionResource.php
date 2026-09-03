<?php

namespace App\Filament\Resources\WalletTransactions;

use App\Models\WalletTransaction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

use App\Filament\Resources\WalletTransactions\Pages;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance & Audits';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('wallet.holder_type')
                    ->label('Holder Tier')
                    ->formatStateUsing(fn ($state) => class_basename($state ?? 'System'))
                    ->badge()
                    ->color(fn (string $state): string => str_contains($state, 'Store') ? 'warning' : 'info'),
                TextColumn::make('wallet.holder.name')
                    ->label('Holder Name')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'credit' => 'success',
                        'debit' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('amount')
                    ->money('NGN')
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('reference')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'credit' => 'Credit Deposits',
                        'debit' => 'Debit Transactions',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'success' => 'Successful',
                        'failed' => 'Failed',
                        'pending' => 'Pending',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWalletTransactions::route('/'),
        ];
    }
}
