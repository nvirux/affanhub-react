<?php

namespace App\Filament\Merchant\Pages;

use App\Models\ActivityLog;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use UnitEnum;
use BackedEnum;

class StoreActivityLogs extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Activity Logs';

    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.merchant.pages.store-activity-logs';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ActivityLog::query()->where('tenant_id', Filament::getTenant()?->getKey())
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Timestamp')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('causer.name')
                    ->label('Performed By')
                    ->placeholder('System / Automated')
                    ->searchable(),
                TextColumn::make('event')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'manual_wallet_credit', 'admin_manual_capital_credit' => 'success',
                        'manual_wallet_debit', 'admin_manual_capital_debit' => 'warning',
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
            ])
            ->filters([
                SelectFilter::make('event')
                    ->options([
                        'manual_wallet_credit' => 'Manual Customer Credit',
                        'manual_wallet_debit' => 'Manual Customer Debit',
                        'admin_manual_capital_credit' => 'Admin Capital Credit',
                        'admin_manual_capital_debit' => 'Admin Capital Debit',
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
                        TextInput::make('event')
                            ->label('Event'),
                        TextInput::make('causer_name')
                            ->label('Performed By')
                            ->placeholder('System / Automated')
                            ->formatStateUsing(fn ($record) => $record->causer?->name ?? 'System / Automated'),
                        Textarea::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                        Textarea::make('properties')
                            ->label('Changes / Details')
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state)
                            ->rows(8)
                            ->columnSpanFull(),
                    ])
            ])
            ->defaultSort('created_at', 'desc');
    }
}
