<?php

namespace App\Filament\Resources\VirtualAccounts;

use App\Models\VirtualAccount;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VirtualAccountResource extends Resource
{
    protected static ?string $model = VirtualAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance & Audits';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('holder_type')
                    ->label('Holder Type')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge()
                    ->color(fn (string $state): string => str_contains($state, 'Store') ? 'warning' : 'info'),
                TextColumn::make('holder.name')
                    ->label('Holder Name')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bank_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('account_number')
                    ->copyable()
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('account_name')
                    ->searchable(),
                TextColumn::make('provider')
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('holder_type')
                    ->label('Filter by Holder Tier')
                    ->options([
                        'App\Models\Store' => 'Merchant Stores',
                        'App\Models\User' => 'Storefront Customers',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active Accounts',
                        'inactive' => 'Inactive Accounts',
                    ]),
                SelectFilter::make('bank_name')
                    ->options([
                        'PalmPay' => 'PalmPay',
                        'Wema Bank' => 'Wema Bank',
                        'Moniepoint' => 'Moniepoint',
                    ]),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVirtualAccounts::route('/'),
        ];
    }
}
