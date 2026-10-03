<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreSlipResource\Pages\ListStoreSlips;
use App\Models\StoreSlip;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class StoreSlipResource extends Resource
{
    protected static ?string $model = StoreSlip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|UnitEnum|null $navigationGroup = 'Identity Services';

    protected static ?string $navigationLabel = 'Identity Slips Pricing';

    protected static ?string $modelLabel = 'Identity Slip';

    protected static ?string $pluralModelLabel = 'Identity Slips Pricing';

    protected static ?string $tenantRelationshipName = 'storeSlips';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('selling_price')
                ->label('Customer Selling Price (₦)')
                ->numeric()
                ->prefix('₦')
                ->required(),
            Toggle::make('is_enabled')
                ->label('Enable on Storefront')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slip.service.name')
                    ->label('Service')
                    ->badge()
                    ->color(fn (?string $state) => str_contains(strtolower($state ?? ''), 'nin') ? 'success' : 'info')
                    ->icon(fn (?string $state) => str_contains(strtolower($state ?? ''), 'nin') ? 'heroicon-o-identification' : 'heroicon-o-user')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('slip.name')
                    ->label('Slip Type')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (StoreSlip $record) => $record->slip?->description),

                TextColumn::make('wholesale_cost')
                    ->label('Your Cost (₦)')
                    ->badge()
                    ->color('purple')
                    ->state(fn (StoreSlip $record) => '₦'.number_format($record->getWholesaleCost(), 2)),

                TextInputColumn::make('selling_price')
                    ->label('Your Store Price (₦)')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),

                TextColumn::make('est_profit')
                    ->label('Est. Profit (₦)')
                    ->badge()
                    ->color('success')
                    ->state(fn (StoreSlip $record) => '₦'.number_format($record->getEstProfit(), 2)),

                ToggleColumn::make('is_enabled')
                    ->label('Store Enabled'),
            ])
            ->filters([
                SelectFilter::make('service_id')
                    ->label('Service')
                    ->relationship('slip.service', 'name'),

                TernaryFilter::make('is_enabled')
                    ->label('Enabled Status'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_enable')
                        ->label('Enable Selected')
                        ->icon('heroicon-o-check-circle')
                        ->action(function ($records) {
                            $records->each->update(['is_enabled' => true]);
                            Notification::make()->title('Selected slips enabled')->success()->send();
                        }),

                    BulkAction::make('bulk_disable')
                        ->label('Disable Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['is_enabled' => false]);
                            Notification::make()->title('Selected slips disabled')->warning()->send();
                        }),
                ]),
            ])
            ->emptyStateHeading('No identity slips found')
            ->emptyStateDescription('Identity slips will automatically appear once configured.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoreSlips::route('/'),
        ];
    }
}
