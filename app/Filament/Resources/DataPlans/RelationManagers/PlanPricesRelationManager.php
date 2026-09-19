<?php

namespace App\Filament\Resources\DataPlans\RelationManagers;

use App\Models\Plan;
use App\Models\PlanDataPrice;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class PlanPricesRelationManager extends RelationManager
{
    protected static string $relationship = 'planPrices';

    protected static ?string $title = 'Tier Wholesale Pricing (Plan Prices)';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('plan_id')
                ->label('Subscription / Membership Tier')
                ->options(Plan::where('is_active', true)->pluck('name', 'id'))
                ->required()
                ->searchable()
                ->unique(
                    table: 'plan_data_prices',
                    column: 'plan_id',
                    modifyRuleUsing: fn ($rule) => $rule->where('data_plan_id', $this->getOwnerRecord()->id),
                    ignoreRecord: true
                ),
            TextInput::make('wholesale_price')
                ->label('Tier Wholesale Price (₦)')
                ->numeric()
                ->prefix('₦')
                ->required()
                ->placeholder('e.g., 210.00'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plan.name')
                    ->label('Membership Tier')
                    ->badge()
                    ->color('purple')
                    ->sortable()
                    ->weight('bold'),
                TextInputColumn::make('wholesale_price')
                    ->label('Tier Wholesale Price (₦)')
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Tier Price'),
                Action::make('populate_all_tiers')
                    ->label('⚡ Auto-Populate All Tiers')
                    ->icon('heroicon-o-bolt')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Auto-Populate All Membership Tiers')
                    ->modalDescription("This will create tier wholesale prices for all active plans that don't have a price set yet, using this data plan's base selling price. Continue?")
                    ->action(function (): void {
                        $dataPlan = $this->getOwnerRecord();
                        $plans = Plan::where('is_active', true)->get();
                        $basePrice = $dataPlan->selling_price ?? $dataPlan->default_retail_price ?? 0;
                        $added = 0;

                        foreach ($plans as $plan) {
                            $exists = PlanDataPrice::where('plan_id', $plan->id)
                                ->where('data_plan_id', $dataPlan->id)
                                ->exists();

                            if (! $exists) {
                                PlanDataPrice::create([
                                    'plan_id' => $plan->id,
                                    'data_plan_id' => $dataPlan->id,
                                    'wholesale_price' => $basePrice,
                                ]);
                                $added++;
                            }
                        }

                        Notification::make()
                            ->title("Added {$added} Tier Price(s)")
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
