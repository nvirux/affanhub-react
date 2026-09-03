<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('store_id')
                ->relationship('store', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Select::make('plan_id')
                ->relationship('plan', 'name')
                ->required()
                ->preload(),
            TextInput::make('price')
                ->numeric()
                ->prefix('₦')
                ->required(),
            Select::make('billing_interval')
                ->options([
                    'month' => 'Monthly',
                    'year' => 'Yearly',
                ])
                ->required(),
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
}
