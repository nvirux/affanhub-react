<?php

namespace App\Filament\Resources\Domains\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DomainsTable
{
    public static function configure(Table $table): Table
    {
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';

        return $table
            ->columns([
                TextColumn::make('domain')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-globe-alt'),
                TextColumn::make('tenant.name')
                    ->label('Store Tenant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->state(fn ($record): string => str_ends_with($record->domain, '.'.$baseDomain) ? 'subdomain' : 'custom_domain')
                    ->color(fn (string $state): string => $state === 'custom_domain' ? 'success' : 'primary')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'custom_domain' => 'Custom Domain',
                        'subdomain' => 'Subdomain',
                        default => $state
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tenant_id')
                    ->label('Filter by Store')
                    ->relationship('tenant', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
