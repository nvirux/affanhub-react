<?php

namespace App\Filament\Merchant\Resources\CustomerResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VirtualAccountsRelationManager extends RelationManager
{
    protected static string $relationship = 'virtualAccounts';

    protected static ?string $title = 'Dedicated Virtual Accounts';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('account_number')
            ->columns([
                TextColumn::make('bank_name')
                    ->label('Bank Name')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('account_number')
                    ->label('Account Number')
                    ->copyable()
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('account_name')
                    ->label('Account Name')
                    ->searchable(),
                TextColumn::make('provider')
                    ->label('Gateway')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
