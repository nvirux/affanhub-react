<?php

namespace App\Filament\Resources\DataPlans;

use App\Models\DataPlan;
use App\Models\Network;
use App\Models\DataType;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;
use App\Filament\Resources\DataPlans\Pages;

class DataPlanResource extends Resource
{
    protected static ?string $model = DataPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static UnitEnum|string|null $navigationGroup = 'VTU Catalog';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('network_id')
                    ->label('Network')
                    ->options(Network::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('data_type_id')
                    ->label('Data Type')
                    ->options(DataType::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('name')
                    ->label('Plan Name')
                    ->required()
                    ->placeholder('e.g., 1.0 GB'),
                TextInput::make('size_mb')
                    ->label('Size (MB)')
                    ->numeric()
                    ->required()
                    ->placeholder('1024'),
                TextInput::make('validity')
                    ->label('Validity')
                    ->required()
                    ->placeholder('e.g., 30 Days, 24 Hours'),
                TextInput::make('cost_price')
                    ->label('Upstream Provider Cost (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->nullable()
                    ->placeholder('e.g., 210.00'),
                TextInput::make('selling_price')
                    ->label('Base Wholesale Price (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->nullable()
                    ->placeholder('e.g., 220.00'),
                TextInput::make('default_retail_price')
                    ->label('Default Customer Retail Price (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->required()
                    ->placeholder('e.g., 250.00'),
                TextInput::make('plan_code')
                    ->label('API Plan Code')
                    ->nullable()
                    ->placeholder('e.g., 1001'),
                Toggle::make('is_best_offer')
                    ->label('HOT 🔥 Best Offer / Featured')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Global Active Status')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('network.name')
                    ->label('Network')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Plan Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('dataType.name')
                    ->label('Type')
                    ->badge()
                    ->color('warning')
                    ->searchable(),
                TextInputColumn::make('cost_price')
                    ->label('Provider Cost (₦)')
                    ->rules(['nullable', 'numeric', 'min:0'])
                    ->sortable(),
                TextInputColumn::make('selling_price')
                    ->label('Wholesale Price (₦)')
                    ->rules(['nullable', 'numeric', 'min:0'])
                    ->sortable(),
                TextInputColumn::make('default_retail_price')
                    ->label('Default Retail Price (₦)')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),
                TextColumn::make('validity')
                    ->label('Validity')
                    ->searchable(),
                ToggleColumn::make('is_best_offer')
                    ->label('HOT 🔥 Best Offer'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('network_id')
                    ->label('Network')
                    ->relationship('network', 'name'),
                SelectFilter::make('data_type_id')
                    ->label('Data Type')
                    ->relationship('dataType', 'name'),
                TernaryFilter::make('is_best_offer')
                    ->label('Best Offer / HOT 🔥'),
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_best_offer')
                        ->label('Set as HOT 🔥 Best Offer')
                        ->icon('heroicon-o-fire')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_best_offer' => true]);
                            }
                            Notification::make()->title('Selected plans marked as Best Offer')->success()->send();
                        }),
                    BulkAction::make('remove_best_offer')
                        ->label('Remove Best Offer Status')
                        ->icon('heroicon-o-minus')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_best_offer' => false]);
                            }
                            Notification::make()->title('Selected plans removed from Best Offers')->success()->send();
                        }),
                    BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_active' => true]);
                            }
                            Notification::make()->title('Selected plans activated')->success()->send();
                        }),
                    BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_active' => false]);
                            }
                            Notification::make()->title('Selected plans deactivated')->success()->send();
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataPlans::route('/'),
        ];
    }
}
