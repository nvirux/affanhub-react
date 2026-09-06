<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\SettlementAccountResource\Pages\ListSettlementAccounts;
use App\Models\SettlementAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use UnitEnum;

class SettlementAccountResource extends Resource
{
    protected static ?string $model = SettlementAccount::class;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Settlement Banks';

    protected static ?string $modelLabel = 'Settlement Bank Account';

    protected static ?int $navigationSort = 15;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date Submitted')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('bank_name')
                    ->label('Bank Name')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('account_number')
                    ->label('Account Number')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('account_name')
                    ->label('Account Holder Name')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Verification Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'approved' => '🟢 Approved',
                        'rejected' => '🔴 Rejected',
                        default => '🟡 Pending Approval',
                    })
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active Payout Bank')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),
                TextColumn::make('admin_notes')
                    ->label('Notes / Rejection Reason')
                    ->placeholder('None')
                    ->wrap(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSettlementAccounts::route('/'),
        ];
    }
}
