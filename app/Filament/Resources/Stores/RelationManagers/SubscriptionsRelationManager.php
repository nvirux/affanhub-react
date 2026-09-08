<?php

namespace App\Filament\Resources\Stores\RelationManagers;

use App\Models\Plan;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'subscriptions';

    protected static ?string $title = 'Store Subscriptions';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('plan_id')
                ->relationship('plan', 'name')
                ->required()
                ->live()
                ->afterStateUpdated(function (Select $component, $state, callable $set) {
                    $plan = Plan::find($state);
                    if ($plan) {
                        $set('price', $plan->price_monthly);
                    }
                }),
            TextInput::make('price')
                ->numeric()
                ->prefix('₦')
                ->required(),
            Select::make('billing_interval')
                ->options([
                    'month' => 'Monthly',
                    'year' => 'Yearly',
                ])
                ->required()
                ->default('month'),
            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'trialing' => 'Trialing',
                    'expired' => 'Expired',
                    'cancelled' => 'Cancelled',
                ])
                ->required()
                ->default('active'),
            DateTimePicker::make('starts_at')
                ->required()
                ->default(now()),
            DateTimePicker::make('ends_at'),
            DateTimePicker::make('trial_ends_at'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->sortable(),
                TextColumn::make('price')
                    ->money('NGN')
                    ->sortable(),
                TextColumn::make('billing_interval')
                    ->label('Interval')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'year' ? 'success' : 'primary')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state).'ly'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'trialing' => 'info',
                        'expired' => 'danger',
                        'cancelled' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Lifetime'),
            ])
            ->headerActions([
                CreateAction::make()->label('Add/Renew Subscription'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('starts_at', 'desc');
    }
}
