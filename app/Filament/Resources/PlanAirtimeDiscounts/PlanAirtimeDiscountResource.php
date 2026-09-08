<?php

namespace App\Filament\Resources\PlanAirtimeDiscounts;

use App\Filament\Resources\PlanAirtimeDiscounts\Pages\ListPlanAirtimeDiscounts;
use App\Models\Network;
use App\Models\Plan;
use App\Models\PlanAirtimeDiscount;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class PlanAirtimeDiscountResource extends Resource
{
    protected static ?string $model = PlanAirtimeDiscount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static UnitEnum|string|null $navigationGroup = 'VTU Catalog';

    protected static ?string $navigationLabel = 'Tier Airtime Pricing';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('plan_id')
                    ->label('Subscription / Membership Tier')
                    ->options(Plan::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Select::make('network_id')
                    ->label('Telecom Network')
                    ->options(Network::pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('wholesale_discount')
                    ->label('Tier Wholesale Discount (%)')
                    ->numeric()
                    ->suffix('%')
                    ->required()
                    ->placeholder('e.g., 2.80'),
                TextInput::make('min_amount')
                    ->label('Custom Min Amount (Optional ₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->nullable()
                    ->placeholder('Inherits global if empty'),
                TextInput::make('max_amount')
                    ->label('Custom Max Amount (Optional ₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->nullable()
                    ->placeholder('Inherits global if empty'),
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
                TextColumn::make('network.name')
                    ->label('Network')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                TextInputColumn::make('wholesale_discount')
                    ->label('Tier Wholesale Discount (%)')
                    ->rules(['required', 'numeric', 'min:0', 'max:100'])
                    ->sortable(),
                TextInputColumn::make('min_amount')
                    ->label('Min (₦)')
                    ->placeholder('Global default')
                    ->rules(['nullable', 'numeric', 'min:1'])
                    ->sortable(),
                TextInputColumn::make('max_amount')
                    ->label('Max (₦)')
                    ->placeholder('Global default')
                    ->rules(['nullable', 'numeric', 'min:1'])
                    ->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('plan_id')
                    ->label('Filter by Plan Tier')
                    ->options(Plan::pluck('name', 'id')),
                SelectFilter::make('network_id')
                    ->label('Filter by Network')
                    ->options(Network::pluck('name', 'id')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlanAirtimeDiscounts::route('/'),
        ];
    }
}
