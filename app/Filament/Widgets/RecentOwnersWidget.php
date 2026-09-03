<?php

namespace App\Filament\Widgets;

use App\Models\Owner;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOwnersWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Owner::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('name')
                    ->weight('bold'),
                TextColumn::make('email')
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('phone')
                    ->placeholder('No phone number'),
                TextColumn::make('stores_count')
                    ->label('Stores Owned')
                    ->counts('stores')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('created_at')
                    ->label('Registered At')
                    ->dateTime(),
            ]);
    }
}
