<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\WithdrawalResource\Pages\ListWithdrawals;
use App\Models\Withdrawal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Store Management';

    protected static ?string $navigationLabel = 'Profit Withdrawals';

    protected static ?string $modelLabel = 'Profit Withdrawal';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Request Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('amount')
                    ->label('Amount (₦)')
                    ->money('NGN')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('bank_name')
                    ->label('Bank Name')
                    ->searchable(),
                TextColumn::make('account_number')
                    ->label('Account Number')
                    ->searchable(),
                TextColumn::make('account_name')
                    ->label('Account Name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'approved' => 'info',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWithdrawals::route('/'),
        ];
    }
}
