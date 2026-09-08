<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Pro'),

                TextInput::make('slug')
                    ->required()
                    ->unique(table: 'plans', column: 'slug', ignoreRecord: true)
                    ->alphaDash()
                    ->maxLength(255)
                    ->placeholder('e.g. pro'),

                TextInput::make('price_monthly')
                    ->numeric()
                    ->prefix('₦')
                    ->placeholder('0.00 (Leave null/blank for custom Enterprise plans)'),

                TextInput::make('price_yearly')
                    ->numeric()
                    ->prefix('₦')
                    ->placeholder('0.00 (Leave null/blank for custom Enterprise plans)'),

                TextInput::make('trial_days')
                    ->numeric()
                    ->default(0)
                    ->label('Free Trial Days'),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Is Plan Active?'),

                Textarea::make('description')
                    ->maxLength(500)
                    ->columnSpanFull(),

                Repeater::make('planFeatures')
                    ->relationship('planFeatures')
                    ->schema([
                        Select::make('feature_id')
                            ->relationship('feature', 'name')
                            ->required()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                        TextInput::make('value')
                            ->required()
                            ->placeholder('e.g. 10 or true'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->label('Default Plan Features')
                    ->createItemButtonLabel('Add Feature Default'),
            ]);
    }
}
