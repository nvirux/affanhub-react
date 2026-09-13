<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('store_id')
                ->label('Store Tenant')
                ->relationship('store', 'name')
                ->placeholder('Select a storefront')
                ->searchable()
                ->preload(),
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('phone')
                ->tel()
                ->maxLength(20),
            TextInput::make('bvn')
                ->label('BVN')
                ->maxLength(11),
            TextInput::make('nin')
                ->label('NIN')
                ->maxLength(11),
            TextInput::make('referral_code')
                ->label('Referral Code')
                ->maxLength(50),
            TextInput::make('password')
                ->password()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create')
                ->maxLength(255),
        ]);
    }
}
