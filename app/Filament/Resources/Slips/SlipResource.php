<?php

namespace App\Filament\Resources\Slips;

use App\Filament\Resources\Slips\Pages\CreateSlip;
use App\Filament\Resources\Slips\Pages\EditSlip;
use App\Filament\Resources\Slips\Pages\ListSlips;
use App\Models\Slip;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class SlipResource extends Resource
{
    protected static ?string $model = Slip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static UnitEnum|string|null $navigationGroup = '⚡ VTU & Services';

    protected static ?string $navigationLabel = 'Identity Slips';

    protected static ?string $modelLabel = 'Identity Slip';

    protected static ?string $pluralModelLabel = 'Identity Slips (NIN & BVN)';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->required()
                    ->searchable(),

                TextInput::make('name')
                    ->label('Slip Name')
                    ->placeholder('e.g., Standard Slip')
                    ->required(),

                TextInput::make('slug')
                    ->label('Slug / Identifier')
                    ->placeholder('e.g., standard')
                    ->required(),

                TextInput::make('badge')
                    ->label('Badge')
                    ->placeholder('e.g., Official A4'),

                TextInput::make('color')
                    ->label('Color Theme')
                    ->placeholder('e.g., emerald, blue, amber, slate')
                    ->default('blue'),

                TextInput::make('cost_price')
                    ->label('Provider Cost Price (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->required()
                    ->helperText('What the upstream verification provider charges our platform.'),

                TextInput::make('selling_price')
                    ->label('Selling Price to Merchants (₦)')
                    ->numeric()
                    ->prefix('₦')
                    ->required()
                    ->helperText('Wholesale price charged to store merchants when their customers verify.'),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(1),

                Toggle::make('is_popular')
                    ->label('Mark as Popular / Recommended')
                    ->default(false),

                Toggle::make('is_active')
                    ->label('Active Status')
                    ->default(true),

                TagsInput::make('features')
                    ->label('Slip Key Features')
                    ->placeholder('Add feature and press Enter')
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.name')
                    ->label('Service')
                    ->badge()
                    ->color(fn (?string $state) => str_contains(strtolower($state ?? ''), 'nin') ? 'success' : 'info')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Slip Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Slip $record) => $record->description),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('slate'),

                TextColumn::make('cost_price')
                    ->label('Provider Cost (₦)')
                    ->state(fn (Slip $record) => '₦'.number_format((float) $record->cost_price, 2))
                    ->sortable(),

                TextInputColumn::make('selling_price')
                    ->label('Merchant Wholesale Price (₦)')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),

                TextColumn::make('platform_margin')
                    ->label('Platform Margin (₦)')
                    ->badge()
                    ->color('purple')
                    ->state(fn (Slip $record) => '₦'.number_format((float) $record->selling_price - (float) $record->cost_price, 2)),

                IconColumn::make('is_popular')
                    ->label('Popular')
                    ->boolean(),

                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                SelectFilter::make('service_id')
                    ->label('Filter by Service')
                    ->relationship('service', 'name'),
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
            'index' => ListSlips::route('/'),
            'create' => CreateSlip::route('/create'),
            'edit' => EditSlip::route('/{record}/edit'),
        ];
    }
}
