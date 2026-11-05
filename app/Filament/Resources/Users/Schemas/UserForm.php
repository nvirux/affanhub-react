<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Customer Profile & Tenant')
                ->description('Personal identification and storefront membership')
                ->schema([
                    Select::make('store_id')
                        ->label('Storefront Tenant')
                        ->relationship('store', 'name')
                        ->placeholder('Platform User (No Store Assigned)')
                        ->searchable()
                        ->preload(),

                    TextInput::make('name')
                        ->label('Full Name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->label('Phone Number')
                        ->tel()
                        ->maxLength(20),
                ])
                ->columns(2),

            Section::make('KYC & Referral Tracking')
                ->description('Regulatory identity records and referral associations')
                ->schema([
                    TextInput::make('bvn')
                        ->label('Bank Verification Number (BVN)')
                        ->maxLength(11)
                        ->password()
                        ->revealable(),

                    TextInput::make('nin')
                        ->label('National Identification Number (NIN)')
                        ->maxLength(11)
                        ->password()
                        ->revealable(),

                    TextInput::make('referral_code')
                        ->label('Assigned Referral Code')
                        ->maxLength(50),

                    Select::make('referred_by')
                        ->label('Referred By (Upline Customer)')
                        ->relationship('referrer', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('None (Direct Registration)'),
                ])
                ->columns(2),

            Section::make('Security & Account Access')
                ->description('Control login privileges and credentials')
                ->schema([
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->revealable()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->helperText(fn (string $context): string => $context === 'edit' ? 'Leave blank to keep current password.' : 'Initial login password.')
                        ->maxLength(255),

                    Toggle::make('is_active')
                        ->label('Account Active')
                        ->helperText('When disabled, user is immediately blocked from logging in or making transactions.')
                        ->default(true),

                    Toggle::make('login_pin_enabled')
                        ->label('Require Login PIN')
                        ->helperText('Enforces 4-digit quick PIN login on customer mobile and web portals.')
                        ->default(false),
                ])
                ->columns(2),
        ]);
    }
}
