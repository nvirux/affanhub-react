<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreAirtimeCashSettingResource\Pages\ListStoreAirtimeCashSettings;
use App\Models\StoreAirtimeCashSetting;
use BackedEnum;
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

class StoreAirtimeCashSettingResource extends Resource
{
    protected static ?string $model = StoreAirtimeCashSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPathRoundedSquare;

    protected static string|UnitEnum|null $navigationGroup = 'VTU Services';

    protected static ?string $slug = 'airtime-cash-settings';

    protected static ?string $navigationLabel = 'Airtime to Cash Rates';

    protected static ?string $modelLabel = 'Airtime to Cash Rate';

    protected static ?string $tenantRelationshipName = 'storeAirtimeCashSettings';

    protected static ?string $tenantOwnershipRelationshipName = 'store';

    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return 'New';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('receiving_phone')
                ->label('Merchant Receiving SIM / Phone Number')
                ->placeholder('e.g. 08012345678')
                ->tel()
                ->helperText('Customers will transfer their airtime to this phone number.')
                ->nullable(),
            TextInput::make('exchange_rate')
                ->label('Cash Conversion Rate (%)')
                ->numeric()
                ->suffix('%')
                ->required()
                ->helperText('e.g. 80.00% means customer gets ₦800 for ₦1,000 airtime.'),
            TextInput::make('min_amount')
                ->label('Minimum Airtime (₦)')
                ->numeric()
                ->prefix('₦')
                ->default(500),
            TextInput::make('max_amount')
                ->label('Maximum Airtime (₦)')
                ->numeric()
                ->prefix('₦')
                ->default(50000),
            TextInput::make('ussd_code_template')
                ->label('Custom USSD / Transfer Instruction')
                ->placeholder('Leave blank to use default network share instructions')
                ->nullable(),
            Toggle::make('is_enabled')
                ->label('Enable for Store Customers')
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
                    ->color(fn ($record) => match (strtolower($record->network?->slug ?? '')) {
                        'mtn' => 'warning',
                        'airtel' => 'danger',
                        'glo' => 'success',
                        '9mobile', 'etisalat' => 'info',
                        default => 'gray',
                    })
                    ->sortable()
                    ->weight('bold'),

                TextInputColumn::make('receiving_phone')
                    ->label('Receiving SIM Number')
                    ->placeholder('Enter phone number (e.g. 080...)')
                    ->rules(['nullable', 'string', 'max:20'])
                    ->searchable(),

                TextInputColumn::make('exchange_rate')
                    ->label('Customer Cash Rate (%)')
                    ->placeholder('80.00')
                    ->rules(['required', 'numeric', 'min:1', 'max:100'])
                    ->sortable(),

                TextColumn::make('merchant_margin')
                    ->label('Your Profit Margin')
                    ->badge()
                    ->color('success')
                    ->state(function (StoreAirtimeCashSetting $record) {
                        $rate = (float) $record->exchange_rate;
                        $margin = max(0, 100 - $rate);

                        return number_format($margin, 2).'% profit';
                    }),

                TextInputColumn::make('min_amount')
                    ->label('Min (₦)')
                    ->placeholder('₦500')
                    ->rules(['nullable', 'numeric', 'min:1']),

                TextInputColumn::make('max_amount')
                    ->label('Max (₦)')
                    ->placeholder('₦50,000')
                    ->rules(['nullable', 'numeric', 'min:1']),

                ToggleColumn::make('is_enabled')
                    ->label('Active on Store'),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoreAirtimeCashSettings::route('/'),
        ];
    }
}
