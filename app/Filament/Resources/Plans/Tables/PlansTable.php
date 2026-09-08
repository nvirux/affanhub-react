<?php

namespace App\Filament\Resources\Plans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price_monthly')
                    ->label('Monthly Price')
                    ->money('NGN')
                    ->placeholder('Custom/Contract')
                    ->sortable(),

                TextColumn::make('price_yearly')
                    ->label('Yearly Price')
                    ->money('NGN')
                    ->placeholder('Custom/Contract')
                    ->sortable(),

                TextColumn::make('trial_days')
                    ->label('Trial (Days)')
                    ->suffix(' days')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
