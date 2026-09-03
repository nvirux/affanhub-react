<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentUsersWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('name')
                    ->weight('bold'),
                TextColumn::make('email')
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('store.name')
                    ->label('Registered Tenant Store')
                    ->badge()
                    ->color('success')
                    ->placeholder('Platform / Central'),
                TextColumn::make('created_at')
                    ->label('Registered At')
                    ->dateTime(),
            ]);
    }
}
