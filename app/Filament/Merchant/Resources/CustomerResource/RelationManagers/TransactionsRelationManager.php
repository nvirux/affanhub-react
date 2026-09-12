<?php

namespace App\Filament\Merchant\Resources\CustomerResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $title = 'Service Purchases (Data & Airtime)';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference')
            ->columns([
                TextColumn::make('service_type')
                    ->label('Service')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'airtime' => 'warning',
                        'data' => 'info',
                        'cable' => 'success',
                        'electricity' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('recipient')
                    ->label('Recipient')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Customer Paid')
                    ->money('NGN')
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('profit')
                    ->label('Your Profit')
                    ->money('NGN')
                    ->color('success')
                    ->weight('semibold')
                    ->sortable(),
                TextColumn::make('reference')
                    ->label('Reference')
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'success', 'successful', 'completed' => 'success',
                        'pending', 'processing' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
