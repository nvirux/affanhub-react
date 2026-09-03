<?php

namespace App\Filament\Resources\Features\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Staff Limit'),
                
                TextInput::make('slug')
                    ->required()
                    ->unique(table: 'features', column: 'slug', ignoreRecord: true)
                    ->alphaDash()
                    ->maxLength(255)
                    ->placeholder('e.g. staff_limit'),
                
                Select::make('type')
                    ->options([
                        'boolean' => 'Boolean (Yes/No)',
                        'integer' => 'Integer (Numbers)',
                        'decimal' => 'Decimal (Decimals)',
                        'string' => 'String (Text)',
                    ])
                    ->required()
                    ->native(false),
                
                TextInput::make('default_value')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. 2 or false'),
                
                Textarea::make('description')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
