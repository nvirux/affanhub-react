<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('FAQ Details')
                    ->description('Create and customize your question and answer.')
                    ->schema([
                        TextInput::make('question')
                            ->label('Question')
                            ->required()
                            ->maxLength(255),

                        Select::make('category')
                            ->label('Category')
                            ->options([
                                'capital' => '₦0 Capital & Float',
                                'operations' => 'Operations & Failover',
                                'pricing' => 'Pricing & Wholesale',
                                'branding' => 'Domains & Branding',
                                'general' => 'General / Account',
                            ])
                            ->default('general')
                            ->required(),

                        Select::make('audience')
                            ->label('Display Audience')
                            ->options([
                                'all' => 'Both (Merchant Dashboard & Public Website)',
                                'merchant' => 'Merchant Dashboard Only',
                                'public' => 'Public Landing Page Only',
                            ])
                            ->default('all')
                            ->required(),

                        TextInput::make('sort_order')
                            ->label('Sort Order (Lower numbers appear first)')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Active & Visible')
                            ->default(true),

                        Textarea::make('answer')
                            ->label('Answer')
                            ->required()
                            ->rows(8)
                            ->helperText('Clear, formatted response for merchants and visitors.'),
                    ]),
            ]);
    }
}
