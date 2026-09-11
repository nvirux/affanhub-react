<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreAirtimeDiscountResource\Pages\ListStoreAirtimeDiscounts;
use App\Models\PlanAirtimeDiscount;
use App\Models\StoreAirtimeDiscount;
use App\Models\Subscription;
use BackedEnum;
use Filament\Facades\Filament;
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

class StoreAirtimeDiscountResource extends Resource
{
    protected static ?string $model = StoreAirtimeDiscount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static string|UnitEnum|null $navigationGroup = 'Products & Pricing';

    protected static ?string $navigationLabel = 'Airtime Pricing';

    protected static ?string $modelLabel = 'Store Airtime Setting';

    protected static ?string $tenantRelationshipName = 'storeAirtimeDiscounts';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('selling_discount')
                ->label('Customer Retail Discount (%)')
                ->numeric()
                ->suffix('%')
                ->placeholder('e.g., 1.50'),
            TextInput::make('min_amount')
                ->label('Custom Store Minimum (₦)')
                ->numeric()
                ->prefix('₦')
                ->nullable()
                ->placeholder('Default 50'),
            TextInput::make('max_amount')
                ->label('Custom Store Maximum (₦)')
                ->numeric()
                ->prefix('₦')
                ->nullable()
                ->placeholder('Default 50,000'),
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
                    ->color('info')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('wholesale_discount')
                    ->label('Your Wholesale Rate')
                    ->badge()
                    ->color('purple')
                    ->state(function (StoreAirtimeDiscount $record) {
                        $store = Filament::getTenant();
                        $subscription = Subscription::where('store_id', $store?->id)
                            ->whereIn('status', ['active', 'trialing'])
                            ->latest()
                            ->first();

                        $wholesale = $record->network->airtimeDiscount?->default_merchant_discount ?? 2.00;
                        if ($subscription && $subscription->plan_id) {
                            $tier = PlanAirtimeDiscount::where('plan_id', $subscription->plan_id)
                                ->where('network_id', $record->network_id)
                                ->first();
                            if ($tier && $tier->wholesale_discount !== null) {
                                $wholesale = $tier->wholesale_discount;
                            }
                        }

                        return number_format((float) $wholesale, 2).'% off';
                    }),
                TextInputColumn::make('selling_discount')
                    ->label('Your Customer Discount (%)')
                    ->rules(['nullable', 'numeric', 'min:0', 'max:100'])
                    ->placeholder(function (StoreAirtimeDiscount $record) {
                        $def = $record->network->airtimeDiscount?->default_retail_discount ?? 1.50;

                        return number_format((float) $def, 2).'% (Default)';
                    })
                    ->sortable(),
                TextColumn::make('est_profit')
                    ->label('Est. Margin')
                    ->badge()
                    ->color('success')
                    ->state(function (StoreAirtimeDiscount $record) {
                        $store = Filament::getTenant();
                        $subscription = Subscription::where('store_id', $store?->id)
                            ->whereIn('status', ['active', 'trialing'])
                            ->latest()
                            ->first();

                        $wholesale = (float) ($record->network->airtimeDiscount?->default_merchant_discount ?? 2.00);
                        if ($subscription && $subscription->plan_id) {
                            $tier = PlanAirtimeDiscount::where('plan_id', $subscription->plan_id)
                                ->where('network_id', $record->network_id)
                                ->first();
                            if ($tier && $tier->wholesale_discount !== null) {
                                $wholesale = (float) $tier->wholesale_discount;
                            }
                        }

                        $customer = $record->selling_discount !== null
                            ? (float) $record->selling_discount
                            : (float) ($record->network->airtimeDiscount?->default_retail_discount ?? 1.50);

                        $margin = max(0, $wholesale - $customer);

                        return number_format($margin, 2).'%';
                    }),
                TextInputColumn::make('min_amount')
                    ->label('Min (₦)')
                    ->placeholder('₦50')
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
            'index' => ListStoreAirtimeDiscounts::route('/'),
        ];
    }
}
