<?php

namespace App\Filament\Resources\PlanDataPrices;

use App\Models\PlanDataPrice;
use App\Models\Plan;
use App\Models\DataPlan;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;
use App\Filament\Resources\PlanDataPrices\Pages;

class PlanDataPriceResource extends Resource
{
    protected static ?string $model = PlanDataPrice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static UnitEnum|string|null $navigationGroup = 'VTU Catalog';

    protected static ?string $navigationLabel = 'Tier Wholesale Pricing';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('plan_id')
                    ->label('Subscription / Reseller Plan Tier')
                    ->options(Plan::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('data_plan_id')
                    ->label('Master Data Plan')
                    ->options(
                        DataPlan::with('network')
                            ->get()
                            ->mapWithKeys(fn ($dp) => [
                                $dp->id => '[' . strtoupper($dp->network->name ?? '') . '] ' . $dp->name . ' (' . $dp->validity . ')'
                            ])
                    )
                    ->required()
                    ->searchable(),
                TextInput::make('wholesale_price')
                    ->label('Wholesale Price (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->required()
                    ->placeholder('e.g., 210.00'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plan.name')
                    ->label('Membership Tier')
                    ->badge()
                    ->color('purple')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dataPlan.network.name')
                    ->label('Network')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dataPlan.name')
                    ->label('Data Plan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('dataPlan.default_retail_price')
                    ->label('Standard Price (₦)')
                    ->money('NGN')
                    ->color('gray')
                    ->sortable(),
                TextInputColumn::make('wholesale_price')
                    ->label('Tier Wholesale Price (₦)')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('plan_id')
                    ->label('Membership Tier')
                    ->relationship('plan', 'name'),
                SelectFilter::make('network')
                    ->label('Network')
                    ->relationship('dataPlan.network', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanDataPrices::route('/'),
        ];
    }
}
