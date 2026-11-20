<?php

namespace App\Filament\Resources\Features\Schemas;

use App\Models\Feature;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('preset')
                    ->label('Pre-select Standard Platform Feature')
                    ->placeholder('Choose a standard platform capability to auto-fill or customize...')
                    ->options([
                        'custom' => 'Custom Feature (Type details manually below)',
                        'staff_limit' => 'Staff Limit (Integer - max staff members allowed)',
                        'custom_domain' => 'Custom Domain (Boolean - custom domain mapping with SSL)',
                        'api_access' => 'API Access (Boolean - merchant developer API integration)',
                        'custom_pricing_margins' => 'Custom Pricing Margins (Boolean - custom markup margins)',
                        'referral_system' => 'Referral & Earn System (Boolean - customer referral program)',
                        'custom_branding' => 'Custom Branding & Theming (Boolean - HEX colors & logo)',
                        'custom_email' => 'Custom Email / Resend (Boolean - custom domain sender)',
                        'mobile_app' => 'Mobile App / APK Builder (Boolean - custom Android APK builder)',
                    ])
                    ->dehydrated(false)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state || $state === 'custom') {
                            return;
                        }

                        $presets = Feature::getStandardFeaturePresets();
                        if (isset($presets[$state])) {
                            $preset = $presets[$state];
                            $set('name', $preset['name']);
                            $set('slug', $state);
                            $set('type', $preset['type']);
                            $set('default_value', $preset['default_value']);
                            $set('description', $preset['description']);
                        }
                    })
                    ->columnSpanFull()
                    ->helperText('Pick a standard platform feature to auto-fill its name, slug, type, default value, and description.'),

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

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Sorting priority for feature listing.'),

                Textarea::make('description')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
