<?php

namespace App\Filament\Resources\Features\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Filters\SelectFilter;

class FeaturesTable
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
                
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'boolean' => 'success',
                        'integer' => 'info',
                        'decimal' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                
                TextColumn::make('default_value')
                    ->label('Global Default'),
                
                TextColumn::make('description')
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'boolean' => 'Boolean (Yes/No)',
                        'integer' => 'Integer (Numbers)',
                        'decimal' => 'Decimal (Decimals)',
                        'string' => 'String (Text)',
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
