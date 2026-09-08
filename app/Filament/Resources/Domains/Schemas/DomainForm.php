<?php

namespace App\Filament\Resources\Domains\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DomainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tenant_id')
                ->label('Store Tenant')
                ->relationship('tenant', 'name')
                ->required()
                ->searchable()
                ->preload(),
            TextInput::make('domain')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->placeholder('e.g. mystore.localhost or customdomain.com'),
        ]);
    }
}
