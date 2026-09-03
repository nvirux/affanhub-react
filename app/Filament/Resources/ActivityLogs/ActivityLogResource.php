<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static UnitEnum|string|null $navigationGroup = 'Platform Management';

    protected static ?int $navigationSort = 100;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Timestamp')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('tenant_id')
                    ->label('Store')
                    ->searchable()
                    ->badge()
                    ->color('primary')
                    ->placeholder('System-wide'),
                TextColumn::make('event')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'domain_added' => 'info',
                        'domain_verified' => 'success',
                        'domain_removed' => 'danger',
                        'domain_primary_changed' => 'warning',
                        'store_settings_updated' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('causer_type')
                    ->label('User Type')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('causer_id')
                    ->label('User ID')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->options([
                        'domain_added' => 'Domain Added',
                        'domain_verified' => 'Domain Verified',
                        'domain_primary_changed' => 'Primary Domain Changed',
                        'domain_removed' => 'Domain Removed',
                        'store_settings_updated' => 'Store Settings Updated',
                    ]),
            ])
            ->actions([
                ViewAction::make()
                    ->form([
                        TextInput::make('created_at')
                            ->label('Timestamp'),
                        TextInput::make('tenant_id')
                            ->label('Store ID')
                            ->placeholder('System-wide'),
                        TextInput::make('event')
                            ->label('Event'),
                        TextInput::make('causer_name')
                            ->label('Performed By')
                            ->placeholder('System / Automated')
                            ->formatStateUsing(fn ($record) => $record->causer?->name ?? 'System / Automated'),
                        TextInput::make('causer_type')
                            ->label('User Model')
                            ->placeholder('System/Anonymous'),
                        TextInput::make('causer_id')
                            ->label('User ID'),
                        Textarea::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                        Textarea::make('properties')
                            ->label('Properties / Payload')
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)
                            ->rows(8)
                            ->columnSpanFull(),
                    ])
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }
}
