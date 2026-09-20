<?php

namespace App\Filament\Resources\StoreMobileApps\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StoreMobileAppForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('App Identity')
                    ->description('Configuration and branding for the mobile application.')
                    ->schema([
                        Select::make('store_id')
                            ->label('Store')
                            ->relationship('store', 'name')
                            ->searchable()
                            ->required()
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),

                        TextInput::make('app_name')
                            ->label('App Name')
                            ->required()
                            ->maxLength(30),

                        TextInput::make('package_id')
                            ->label('Package ID (Android Application ID)')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g., com.affanhub.mystore'),

                        FileUpload::make('app_icon_path')
                            ->label('App Icon')
                            ->image()
                            ->disk('public')
                            ->directory('store-app-icons')
                            ->avatar(),
                    ]),

                Section::make('Build & Artifacts')
                    ->description('Release versioning and cloud compile output.')
                    ->schema([
                        TextInput::make('version_code')
                            ->label('Version Code (Integer)')
                            ->numeric()
                            ->required()
                            ->default(1),

                        TextInput::make('version_name')
                            ->label('Version Name')
                            ->required()
                            ->default('1.0.0'),

                        Select::make('status')
                            ->label('Build Status')
                            ->options([
                                'building' => 'Building',
                                'ready' => 'Ready',
                                'failed' => 'Failed',
                            ])
                            ->required(),

                        TextInput::make('price_paid')
                            ->label('APK Build Fee Paid (₦)')
                            ->numeric()
                            ->prefix('₦'),

                        TextInput::make('apk_download_url')
                            ->label('APK Download URL')
                            ->url()
                            ->maxLength(1000),

                        TextInput::make('aab_download_url')
                            ->label('Google Play Bundle (.aab) Download URL')
                            ->url()
                            ->maxLength(1000),

                        TextInput::make('failure_reason')
                            ->label('Failure Reason')
                            ->maxLength(500),
                    ]),

                Section::make('Google Play Store Publishing')
                    ->description('Google Play submission lifecycle and store listing.')
                    ->schema([
                        Select::make('playstore_status')
                            ->label('Play Store Status')
                            ->options([
                                'not_requested' => 'Not Requested',
                                'pending_submission' => 'Pending Submission',
                                'submitted' => 'Submitted to Google',
                                'published' => 'Published Live',
                                'rejected' => 'Rejected',
                            ])
                            ->default('not_requested')
                            ->required(),

                        TextInput::make('playstore_paid')
                            ->label('Play Store Fee Paid (₦)')
                            ->numeric()
                            ->prefix('₦'),

                        TextInput::make('playstore_url')
                            ->label('Google Play Store Live Link')
                            ->url()
                            ->placeholder('https://play.google.com/store/apps/details?id=...'),
                    ]),

                Section::make('Custom Android Signing Keystore')
                    ->description('Optional: Upload store-specific legacy signing keys for apps that are already on the Google Play Store.')
                    ->collapsed()
                    ->schema([
                        FileUpload::make('custom_keystore_path')
                            ->label('Keystore File (.jks or .keystore)')
                            ->disk('local')
                            ->directory('keystores'),

                        TextInput::make('custom_keystore_alias')
                            ->label('Key Alias')
                            ->placeholder('e.g. key0 or upload'),

                        TextInput::make('custom_keystore_password')
                            ->label('Keystore Password')
                            ->password(),
                    ]),
            ]);
    }
}
