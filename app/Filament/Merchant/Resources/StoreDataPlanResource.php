<?php

namespace App\Filament\Merchant\Resources;

use App\Filament\Merchant\Resources\StoreDataPlanResource\Pages\ListStoreDataPlans;
use App\Models\StoreDataPlan;
use App\Models\Subscription;
use App\Models\PlanDataPrice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use UnitEnum;

class StoreDataPlanResource extends Resource
{
    protected static ?string $model = StoreDataPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Store Management';

    protected static ?string $navigationLabel = 'Data Plan Pricing';

    protected static ?string $modelLabel = 'Store Data Plan';

    protected static ?string $tenantRelationshipName = 'storeDataPlans';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('selling_price')
                ->label('Custom Store Price (₦)')
                ->numeric()
                ->prefix('₦')
                ->required(),
            Toggle::make('is_enabled')
                ->label('Enable for Store Customers')
                ->default(true),
            Toggle::make('is_best_offer')
                ->label('HOT 🔥 Best Offer Badge on Storefront')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dataPlan.network.name')
                    ->label('Network')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dataPlan.name')
                    ->label('Plan Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('dataPlan.dataType.name')
                    ->label('Data Type')
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('dataPlan.validity')
                    ->label('Validity')
                    ->searchable(),
                TextColumn::make('wholesale_cost')
                    ->label('Your Cost (₦)')
                    ->badge()
                    ->color('purple')
                    ->state(function (StoreDataPlan $record) {
                        $store = \Filament\Facades\Filament::getTenant();
                        $subscription = Subscription::where('store_id', $store?->id)
                            ->whereIn('status', ['active', 'trialing'])
                            ->latest()
                            ->first();

                        if ($subscription && $subscription->plan_id) {
                            $planPrice = PlanDataPrice::where('plan_id', $subscription->plan_id)
                                ->where('data_plan_id', $record->data_plan_id)
                                ->first();

                            if ($planPrice && $planPrice->wholesale_price !== null) {
                                return '₦' . number_format((float) $planPrice->wholesale_price, 2);
                            }
                        }

                        $baseWholesale = $record->dataPlan->selling_price ?? $record->dataPlan->default_retail_price;
                        return '₦' . number_format((float) $baseWholesale, 2);
                    }),
                TextInputColumn::make('selling_price')
                    ->label('Your Store Price (₦)')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),
                TextColumn::make('est_profit')
                    ->label('Est. Profit (₦)')
                    ->badge()
                    ->color('success')
                    ->state(function (StoreDataPlan $record) {
                        $store = \Filament\Facades\Filament::getTenant();
                        $subscription = Subscription::where('store_id', $store?->id)
                            ->whereIn('status', ['active', 'trialing'])
                            ->latest()
                            ->first();

                        $wholesaleCost = (float) ($record->dataPlan->selling_price ?? $record->dataPlan->default_retail_price);

                        if ($subscription && $subscription->plan_id) {
                            $planPrice = PlanDataPrice::where('plan_id', $subscription->plan_id)
                                ->where('data_plan_id', $record->data_plan_id)
                                ->first();

                            if ($planPrice && $planPrice->wholesale_price !== null) {
                                $wholesaleCost = (float) $planPrice->wholesale_price;
                            }
                        }

                        $profit = (float) $record->selling_price - $wholesaleCost;
                        return '₦' . number_format($profit, 2);
                    }),
                ToggleColumn::make('is_best_offer')
                    ->label('HOT 🔥 Best Offer'),
                ToggleColumn::make('is_enabled')
                    ->label('Store Enabled'),
            ])
            ->filters([
                SelectFilter::make('network')
                    ->label('Network')
                    ->relationship('dataPlan.network', 'name'),
                SelectFilter::make('data_type')
                    ->label('Data Type')
                    ->relationship('dataPlan.dataType', 'name'),
                TernaryFilter::make('is_best_offer')
                    ->label('HOT 🔥 Best Offer'),
                TernaryFilter::make('is_enabled')
                    ->label('Store Enabled'),
            ])
            ->recordActions([
                EditAction::make(),
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
                            Notification::make()->title('Selected plans set as Best Offer')->success()->send();
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
                    BulkAction::make('enable')
                        ->label('Enable Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_enabled' => true]);
                            }
                            Notification::make()->title('Selected plans enabled')->success()->send();
                        }),
                    BulkAction::make('disable')
                        ->label('Disable Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                $record->update(['is_enabled' => false]);
                            }
                            Notification::make()->title('Selected plans disabled')->success()->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoreDataPlans::route('/'),
        ];
    }
}
