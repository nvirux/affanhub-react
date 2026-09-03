<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->description('Manage the basic settings of this tenant storefront.')
                    ->schema([
                        TextInput::make('id')
                            ->label('Store Slug (ID)')
                            ->required()
                            ->unique(table: 'stores', column: 'id', ignoreRecord: true)
                            ->alphaDash()
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->placeholder('e.g. demo'),
                        
                        TextInput::make('name')
                            ->label('Store Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Demo Storefront'),
                        
                        Select::make('owner_id')
                            ->label('Owner (Merchant)')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->required(),
                        
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'suspended' => 'Suspended',
                                'pending' => 'Pending',
                            ])
                            ->required()
                            ->default('active')
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }
}
