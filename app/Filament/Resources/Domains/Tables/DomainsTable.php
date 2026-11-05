<?php

namespace App\Filament\Resources\Domains\Tables;

use App\Mail\MerchantDirectMessageMail;
use App\Models\ActivityLog;
use App\Models\Domain;
use App\Services\Communication\MerchantOutreachService;
use App\Services\Domain\CustomDomainOnboardingService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DomainsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['store.owner', 'tenant.owner']))
            ->columns([
                TextColumn::make('domain')
                    ->label('Domain Hostname')
                    ->icon('heroicon-m-globe-alt')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Domain hostname copied')
                    ->url(fn (Domain $record): string => 'https://'.$record->domain)
                    ->openUrlInNewTab()
                    ->weight('semibold')
                    ->description(function (Domain $record): string {
                        if ($record->is_primary) {
                            return '⭐ Primary Storefront Host';
                        }
                        if ($record->isCustom()) {
                            return 'CNAME -> cname.affanhub.com';
                        }

                        return 'Platform Subdomain';
                    }),

                TextColumn::make('tenant.name')
                    ->label('Store Tenant')
                    ->badge()
                    ->color('amber')
                    ->searchable()
                    ->sortable()
                    ->description(function (Domain $record): ?string {
                        $owner = $record->store?->owner ?? $record->tenant?->owner;
                        if (! $owner) {
                            return null;
                        }

                        return "{$owner->name} ({$owner->email})";
                    }),

                TextColumn::make('type')
                    ->label('Domain Type')
                    ->badge()
                    ->state(fn (Domain $record): string => $record->isCustom() ? 'Custom Domain' : 'Subdomain')
                    ->color(fn (string $state): string => $state === 'Custom Domain' ? 'purple' : 'info'),

                TextColumn::make('verification_status')
                    ->label('DNS & Health')
                    ->badge()
                    ->state(function (Domain $record): string {
                        if (! $record->isCustom()) {
                            return 'Ready (Platform)';
                        }
                        if ($record->isHealthy()) {
                            return 'Verified & Active';
                        }
                        if ($record->verification_status === Domain::VERIFY_PENDING_DNS) {
                            return 'Pending DNS';
                        }
                        if ($record->status === Domain::STATUS_FAILED) {
                            return 'Verification Failed';
                        }

                        return $record->verification_status ?: 'Pending';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Ready (Platform)', 'Verified & Active' => 'success',
                        'Pending DNS' => 'warning',
                        'Verification Failed' => 'danger',
                        default => 'gray',
                    })
                    ->tooltip(fn (Domain $record): ?string => $record->last_verification_message ?: ($record->verification_error ?: null)),

                TextColumn::make('cloudflare')
                    ->label('SSL / CDN')
                    ->badge()
                    ->state(fn (Domain $record): string => ($record->cloudflare_detected || filled($record->cloudflare_hostname_id)) ? 'Cloudflare SaaS' : 'Standard DNS')
                    ->color(fn (string $state): string => $state === 'Cloudflare SaaS' ? 'warning' : 'gray')
                    ->tooltip(fn (Domain $record): ?string => $record->cloudflare_hostname_id ? "Hostname ID: {$record->cloudflare_hostname_id}" : null),

                TextColumn::make('is_routing_enabled')
                    ->label('Routing')
                    ->badge()
                    ->state(fn (Domain $record): string => $record->is_routing_enabled ? 'Enabled' : 'Disabled')
                    ->color(fn (string $state): string => $state === 'Enabled' ? 'success' : 'danger'),

                TextColumn::make('last_resolved_ip')
                    ->label('Resolved IP')
                    ->state(fn (Domain $record): string => $record->last_resolved_ip ?: 'Not resolved')
                    ->description(fn (Domain $record): ?string => $record->last_http_status ? "HTTP {$record->last_http_status}" : null)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Added On')
                    ->dateTime('M d, Y H:i')
                    ->description(fn (Domain $record): string => $record->created_at?->diffForHumans() ?? '')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('tenant_id')
                    ->label('Filter by Store')
                    ->relationship('tenant', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_custom')
                    ->label('Domain Type')
                    ->placeholder('All Domain Types')
                    ->trueLabel('Custom Domains Only')
                    ->falseLabel('Subdomains Only')
                    ->queries(
                        true: fn (Builder $query) => $query->custom(),
                        false: fn (Builder $query) => $query->subdomains(),
                    ),

                TernaryFilter::make('is_routing_enabled')
                    ->label('Traffic Routing')
                    ->trueLabel('Routing Enabled Only')
                    ->falseLabel('Routing Disabled Only')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_routing_enabled', true),
                        false: fn (Builder $query) => $query->where('is_routing_enabled', false),
                    ),

                SelectFilter::make('verification_status')
                    ->label('DNS Verification Status')
                    ->options([
                        Domain::VERIFY_PENDING_DNS => 'Pending DNS',
                        Domain::VERIFY_OWNERSHIP_VERIFIED => 'Ownership Verified',
                        Domain::VERIFY_CONNECTED => 'Connected',
                        Domain::VERIFY_ACTIVE => 'Active',
                        Domain::STATUS_FAILED => 'Failed',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->slideOver(),

                    EditAction::make(),

                    Action::make('test_dns')
                        ->label('Run DNS Diagnostics')
                        ->icon('heroicon-o-arrow-path')
                        ->color('info')
                        ->action(function (Domain $record, CustomDomainOnboardingService $service) {
                            if (! $record->isCustom()) {
                                Notification::make()
                                    ->title("{$record->domain} is a platform subdomain and is always operational.")
                                    ->info()
                                    ->send();

                                return;
                            }

                            try {
                                $service->processOnboarding($record, force: true);
                                $record->refresh();

                                if ($record->isHealthy()) {
                                    Notification::make()
                                        ->title("DNS Check Passed: {$record->domain} is verified and healthy!")
                                        ->success()
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title("DNS Check Note: {$record->last_verification_message}")
                                        ->warning()
                                        ->send();
                                }
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title('DNS Diagnostics Error: '.$e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    Action::make('approve_routing')
                        ->label('Approve & Enable Routing')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn (Domain $record): bool => ! $record->is_routing_enabled || ! $record->is_approved)
                        ->requiresConfirmation()
                        ->modalHeading(fn (Domain $record): string => "Approve Routing for {$record->domain}")
                        ->modalDescription('This will mark the domain as verified, approve custom routing, and begin serving storefront traffic.')
                        ->action(function (Domain $record) {
                            $record->update([
                                'is_approved' => true,
                                'approved_at' => now(),
                                'is_routing_enabled' => true,
                                'is_verified' => true,
                                'verified_at' => $record->verified_at ?? now(),
                                'status' => Domain::STATUS_VERIFIED,
                                'verification_status' => Domain::VERIFY_ACTIVE,
                                'last_verification_message' => 'Manually approved and enabled by Super Admin.',
                            ]);

                            ActivityLog::create([
                                'tenant_id' => $record->tenant_id,
                                'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                'causer_id' => Auth::id(),
                                'event' => 'admin_approved_domain_routing',
                                'description' => "Super Admin approved and enabled traffic routing for domain {$record->domain}",
                                'properties' => ['domain_id' => $record->id, 'domain' => $record->domain],
                            ]);

                            Notification::make()
                                ->title("Routing enabled for {$record->domain}")
                                ->success()
                                ->send();
                        }),

                    Action::make('disable_routing')
                        ->label('Disable / Pause Routing')
                        ->icon('heroicon-o-pause-circle')
                        ->color('danger')
                        ->visible(fn (Domain $record): bool => (bool) $record->is_routing_enabled)
                        ->requiresConfirmation()
                        ->modalHeading(fn (Domain $record): string => "Disable Traffic Routing: {$record->domain}")
                        ->form([
                            TextInput::make('reason')
                                ->label('Reason for Disabling Routing')
                                ->placeholder('e.g. Unresolved DNS errors, phishing report, customer request')
                                ->required(),
                        ])
                        ->action(function (Domain $record, array $data) {
                            $record->update([
                                'is_routing_enabled' => false,
                                'routing_disabled_reason' => $data['reason'],
                                'last_verification_message' => "Routing disabled by Admin: {$data['reason']}",
                            ]);

                            ActivityLog::create([
                                'tenant_id' => $record->tenant_id,
                                'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                'causer_id' => Auth::id(),
                                'event' => 'admin_disabled_domain_routing',
                                'description' => "Super Admin paused traffic routing for {$record->domain}. Reason: {$data['reason']}",
                                'properties' => ['domain_id' => $record->id, 'domain' => $record->domain, 'reason' => $data['reason']],
                            ]);

                            Notification::make()
                                ->title("Routing paused for {$record->domain}")
                                ->warning()
                                ->send();
                        }),

                    Action::make('make_primary')
                        ->label('Make Primary Domain')
                        ->icon('heroicon-o-star')
                        ->color('amber')
                        ->visible(fn (Domain $record): bool => ! $record->is_primary)
                        ->requiresConfirmation()
                        ->modalHeading(fn (Domain $record): string => "Set {$record->domain} as Primary")
                        ->modalDescription('Setting this domain as primary will use it for all canonical store links, receipts, and customer redirection.')
                        ->action(function (Domain $record) {
                            Domain::where('tenant_id', $record->tenant_id)
                                ->where('id', '!=', $record->id)
                                ->update(['is_primary' => false]);

                            $record->update(['is_primary' => true]);

                            Notification::make()
                                ->title("{$record->domain} is now the primary domain for this store.")
                                ->success()
                                ->send();
                        }),

                    Action::make('whatsapp_owner')
                        ->label('WhatsApp Store Owner')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->visible(function (Domain $record): bool {
                            $owner = $record->store?->owner ?? $record->tenant?->owner;

                            return filled($owner?->phone);
                        })
                        ->modalHeading(fn (Domain $record): string => "WhatsApp Owner of {$record->domain}")
                        ->modalSubmitActionLabel('Open WhatsApp Chat ↗')
                        ->form(function (Domain $record) {
                            $owner = $record->store?->owner ?? $record->tenant?->owner;
                            $storeName = $record->store?->name ?? $record->tenant?->name;
                            $templates = MerchantOutreachService::getDomainTemplates($owner->name ?? 'Merchant', $record->domain, $storeName);
                            $options = collect($templates)->mapWithKeys(fn ($item, $key) => [$key => $item['label']])->toArray();
                            $options['custom'] = '✏️ Custom Message';

                            return [
                                TextInput::make('phone')
                                    ->label('WhatsApp Phone Number')
                                    ->default(fn () => $owner?->phone)
                                    ->required()
                                    ->helperText('Formatted automatically for WhatsApp (+234...).'),

                                Select::make('template')
                                    ->label('Choose Message Template')
                                    ->options($options)
                                    ->default($record->isHealthy() ? 'domain_approved' : 'dns_instructions')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) use ($templates) {
                                        if (isset($templates[$state])) {
                                            $set('message', $templates[$state]['message']);
                                        }
                                    }),

                                Textarea::make('message')
                                    ->label('Message Content')
                                    ->default(function () use ($record, $templates) {
                                        $defKey = $record->isHealthy() ? 'domain_approved' : 'dns_instructions';

                                        return $templates[$defKey]['message'] ?? '';
                                    })
                                    ->rows(4)
                                    ->required(),
                            ];
                        })
                        ->action(function (array $data) {
                            $url = MerchantOutreachService::buildWhatsAppUrl($data['phone'], $data['message']);

                            return redirect()->away($url);
                        }),

                    Action::make('send_email_owner')
                        ->label('Email Store Owner')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->visible(function (Domain $record): bool {
                            $owner = $record->store?->owner ?? $record->tenant?->owner;

                            return filled($owner?->email);
                        })
                        ->modalHeading(fn (Domain $record): string => 'Send Domain Update Email')
                        ->modalSubmitActionLabel('Send Email Now')
                        ->form(function (Domain $record) {
                            return [
                                TextInput::make('subject')
                                    ->label('Email Subject')
                                    ->default("Domain Update for {$record->domain}")
                                    ->required(),
                                Textarea::make('body')
                                    ->label('Email Message Body')
                                    ->default("Hello, regarding your domain {$record->domain} connected to your storefront: please note that your domain configuration has been updated. Feel free to contact us if you need any assistance.")
                                    ->rows(5)
                                    ->required(),
                            ];
                        })
                        ->action(function (Domain $record, array $data) {
                            $owner = $record->store?->owner ?? $record->tenant?->owner;

                            try {
                                Mail::to($owner->email)->send(
                                    new MerchantDirectMessageMail($owner, $data['subject'], $data['body'])
                                );

                                ActivityLog::create([
                                    'tenant_id' => $record->tenant_id,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_sent_domain_email',
                                    'description' => "Admin sent domain email regarding {$record->domain} to {$owner->email}",
                                    'properties' => ['domain_id' => $record->id, 'email' => $owner->email, 'subject' => $data['subject']],
                                ]);

                                Notification::make()
                                    ->title("Email sent to {$owner->email} successfully.")
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title('Failed to send email: '.$e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    Action::make('open_url')
                        ->label('Visit Storefront ↗')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->color('gray')
                        ->url(fn (Domain $record): string => 'https://'.$record->domain)
                        ->openUrlInNewTab(),

                    DeleteAction::make()
                        ->before(function (Domain $record, CustomDomainOnboardingService $service) {
                            if ($record->isCustom()) {
                                try {
                                    $service->removeDomain($record);
                                } catch (\Throwable $e) {
                                    // ignore to allow model deletion
                                }
                            }
                        }),
                ])
                    ->label('More Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary')
                    ->button(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
