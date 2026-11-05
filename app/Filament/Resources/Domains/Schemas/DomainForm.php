<?php

namespace App\Filament\Resources\Domains\Schemas;

use App\Models\Domain;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DomainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Storefront & Domain Identity')
                ->description('Assign this hostname to a store and control primary routing')
                ->schema([
                    Select::make('tenant_id')
                        ->label('Store Tenant')
                        ->relationship('tenant', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    TextInput::make('domain')
                        ->label('Domain Name')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->placeholder('e.g. mystore.affanhub.com or brand.com'),

                    Toggle::make('is_primary')
                        ->label('Primary Store Domain')
                        ->helperText('Default domain used in customer links and checkout.')
                        ->default(false),

                    Toggle::make('is_routing_enabled')
                        ->label('Routing Enabled')
                        ->helperText('Allow incoming HTTP requests on this domain to load the storefront.')
                        ->default(true),
                ])
                ->columns(2),

            Section::make('DNS & Cloudflare Verification Records')
                ->description('Technical records, SSL status, and diagnostics')
                ->schema([
                    Select::make('verification_status')
                        ->label('Verification Status')
                        ->options([
                            Domain::VERIFY_PENDING_DNS => 'Pending DNS Records',
                            Domain::VERIFY_OWNERSHIP_VERIFIED => 'Ownership Verified (TXT confirmed)',
                            Domain::VERIFY_CONNECTED => 'Connected & Pointing',
                            Domain::VERIFY_ACTIVE => 'Active & Fully Routing',
                            Domain::STATUS_FAILED => 'Verification Failed',
                        ])
                        ->default(Domain::VERIFY_ACTIVE),

                    TextInput::make('verification_token')
                        ->label('TXT Verification Token')
                        ->disabled()
                        ->placeholder('Generated automatically for custom domains'),

                    Toggle::make('cloudflare_detected')
                        ->label('Cloudflare Managed DNS')
                        ->helperText('Detected if domain is behind Cloudflare DNS proxy.')
                        ->disabled(),

                    TextInput::make('cloudflare_hostname_id')
                        ->label('Cloudflare Custom Hostname ID')
                        ->disabled()
                        ->placeholder('Auto-provisioned on submit'),

                    TextInput::make('last_resolved_ip')
                        ->label('Last Resolved IP')
                        ->disabled(),

                    TextInput::make('last_http_status')
                        ->label('Last HTTP Status')
                        ->disabled(),

                    TextInput::make('last_verification_message')
                        ->label('Last Verification / Diagnostic Note')
                        ->columnSpanFull()
                        ->disabled(),
                ])
                ->columns(2),
        ]);
    }
}
