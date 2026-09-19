<?php

namespace App\Filament\Resources\AirtimeDiscounts\RelationManagers;

use App\Models\Plan;
use App\Models\PlanAirtimeDiscount;
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

class PlanAirtimeDiscountsRelationManager extends RelationManager
{
    protected static string $relationship = 'planAirtimeDiscounts';

    protected static ?string $title = 'Tier Wholesale Discounts (Plan Discounts)';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('plan_id')
                ->label('Subscription / Membership Tier')
                ->options(Plan::where('is_active', true)->pluck('name', 'id'))
                ->required()
                ->searchable()
                ->unique(
                    table: 'plan_airtime_discounts',
                    column: 'plan_id',
                    modifyRuleUsing: fn ($rule) => $rule->where('network_id', $this->getOwnerRecord()->network_id),
                    ignoreRecord: true
                ),
            TextInput::make('wholesale_discount')
                ->label('Tier Wholesale Discount (%)')
                ->numeric()
                ->suffix('%')
                ->required()
                ->placeholder('e.g., 2.50'),
            TextInput::make('min_amount')
                ->label('Minimum Recharge (Optional ₦)')
                ->numeric()
                ->prefix('₦')
                ->nullable()
                ->placeholder('Inherits global default'),
            TextInput::make('max_amount')
                ->label('Maximum Recharge (Optional ₦)')
                ->numeric()
                ->prefix('₦')
                ->nullable()
                ->placeholder('Inherits global default'),
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
                TextInputColumn::make('wholesale_discount')
                    ->label('Wholesale Discount (%)')
                    ->rules(['required', 'numeric', 'min:0', 'max:100'])
                    ->sortable(),
                TextInputColumn::make('min_amount')
                    ->label('Min (₦)')
                    ->placeholder('Default')
                    ->rules(['nullable', 'numeric', 'min:1'])
                    ->sortable(),
                TextInputColumn::make('max_amount')
                    ->label('Max (₦)')
                    ->placeholder('Default')
                    ->rules(['nullable', 'numeric', 'min:1'])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Tier Discount'),
                Action::make('populate_all_tiers')
                    ->label('⚡ Auto-Populate All Tiers')
                    ->icon('heroicon-o-bolt')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Auto-Populate All Membership Tiers')
                    ->modalDescription("This will create tier wholesale discounts for all active plans that don't have a discount set yet, using this network's default merchant discount. Continue?")
                    ->action(function (): void {
                        $airtime = $this->getOwnerRecord();
                        $plans = Plan::where('is_active', true)->get();
                        $baseDiscount = $airtime->default_merchant_discount ?? 0;
                        $added = 0;

                        foreach ($plans as $plan) {
                            $exists = PlanAirtimeDiscount::where('plan_id', $plan->id)
                                ->where('network_id', $airtime->network_id)
                                ->exists();

                            if (! $exists) {
                                PlanAirtimeDiscount::create([
                                    'plan_id' => $plan->id,
                                    'network_id' => $airtime->network_id,
                                    'wholesale_discount' => $baseDiscount,
                                    'min_amount' => $airtime->min_amount,
                                    'max_amount' => $airtime->max_amount,
                                ]);
                                $added++;
                            }
                        }

                        Notification::make()
                            ->title("Added {$added} Tier Discount(s)")
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
