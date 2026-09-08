<?php

namespace App\Filament\Resources\AirtimeDiscounts;

use App\Filament\Resources\AirtimeDiscounts\Pages\ListAirtimeDiscounts;
use App\Models\AirtimeDiscount;
use App\Models\Network;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class AirtimeDiscountResource extends Resource
{
    protected static ?string $model = AirtimeDiscount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static UnitEnum|string|null $navigationGroup = 'VTU Catalog';

    protected static ?string $navigationLabel = 'Airtime Pricing';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('network_id')
                    ->label('Telecom Network')
                    ->options(Network::pluck('name', 'id'))
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('buy_discount')
                    ->label('Provider Buy Discount (%)')
                    ->numeric()
                    ->suffix('%')
                    ->required()
                    ->placeholder('e.g., 3.50'),
                TextInput::make('default_merchant_discount')
                    ->label('Default Merchant Wholesale (%)')
                    ->numeric()
                    ->suffix('%')
                    ->required()
                    ->placeholder('e.g., 2.00'),
                TextInput::make('default_retail_discount')
                    ->label('Default Customer Retail (%)')
                    ->numeric()
                    ->suffix('%')
                    ->required()
                    ->placeholder('e.g., 1.50'),
                TextInput::make('min_amount')
                    ->label('Minimum Recharge Amount (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->default(50.00),
                TextInput::make('max_amount')
                    ->label('Maximum Recharge Amount (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->default(50000.00),
                Toggle::make('is_active')
                    ->label('Airtime Active')
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
                    ->searchable()
                    ->weight('bold'),
                TextInputColumn::make('buy_discount')
                    ->label('Provider Cost (%)')
                    ->rules(['required', 'numeric', 'min:0', 'max:100'])
                    ->sortable(),
                TextInputColumn::make('default_merchant_discount')
                    ->label('Merchant Wholesale (%)')
                    ->rules(['required', 'numeric', 'min:0', 'max:100'])
                    ->sortable(),
                TextInputColumn::make('default_retail_discount')
                    ->label('Customer Retail (%)')
                    ->rules(['required', 'numeric', 'min:0', 'max:100'])
                    ->sortable(),
                TextInputColumn::make('min_amount')
                    ->label('Min (₦)')
                    ->rules(['required', 'numeric', 'min:1'])
                    ->sortable(),
                TextInputColumn::make('max_amount')
                    ->label('Max (₦)')
                    ->rules(['required', 'numeric', 'min:1'])
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active Status'),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAirtimeDiscounts::route('/'),
        ];
    }
}
