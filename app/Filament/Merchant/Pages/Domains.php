<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Domain;
use App\Services\Audit\ActivityLogger;
use App\Services\Domain\CustomDomainOnboardingService;
use App\Services\Domain\DnsVerificationService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class Domains extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Custom Domains';

    protected static ?string $navigationLabel = 'Domains';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.merchant.pages.domains';

    protected function getHeaderActions(): array
    {
        $tenant = Filament::getTenant();
        $hasCustomDomain = (bool) $tenant?->hasFeature('custom_domain');

        if (! $hasCustomDomain) {
            $requiredPlan = method_exists($tenant, 'getFeatureUpgradeRequirement')
                ? $tenant->getFeatureUpgradeRequirement('custom_domain')
                : 'Pro or Enterprise';

            return [
                Action::make('connect_domain_locked')
                    ->label('Connect Custom Domain')
                    ->icon('heroicon-o-lock-closed')
                    ->color('warning')
                    ->modalHeading('Custom Domain Mapping is Locked')
                    ->modalIcon('heroicon-o-lock-closed')
                    ->modalIconColor('warning')
                    ->modalDescription("Custom domain connection is not included in your current plan. Upgrade to {$requiredPlan} to connect your own branded domain (e.g. yourbrand.com).")
                    ->modalSubmitActionLabel('Upgrade Plan')
                    ->action(function () {
                        return redirect()->to(Billing::getUrl());
                    }),
            ];
        }

        return [
            Action::make('connect_domain')
                ->label('Connect Custom Domain')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading('Connect Custom Domain')
                ->modalDescription('Enter the domain or subdomain you want to link to your storefront.')
                ->modalSubmitActionLabel('Add Domain')
                ->form([
                    TextInput::make('domain')
                        ->label('Domain Name')
                        ->placeholder('e.g. mybrandstore.com')
                        ->required()
                        ->rules(['string', 'min:3'])
                        ->helperText('Enter your apex domain (e.g. mystore.com) or subdomain (e.g. shop.mystore.com).'),
                ])
                ->action(function (array $data, CustomDomainOnboardingService $service): void {
                    $tenant = Filament::getTenant();

                    if (! $tenant->hasFeature('custom_domain')) {
                        Notification::make()
                            ->title('Feature Locked')
                            ->body('Custom domain mapping is not available on your current plan.')
                            ->danger()
                            ->send();

                        return;
                    }

                    try {
                        $domain = $service->submitDomain($tenant, $data['domain']);
                        ActivityLogger::log('domain_added', "Added custom domain {$domain->domain}", ['domain' => $domain->domain]);

                        Notification::make()
                            ->title('Domain Added Successfully')
                            ->body('Please configure your DNS records or use 1-Click Cloudflare setup to connect.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error Adding Domain')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function table(Table $table): Table
    {
        $tenant = Filament::getTenant();

        return $table
            ->query(
                Domain::query()->where('tenant_id', $tenant?->getKey())
            )
            ->columns([
                TextColumn::make('domain')
                    ->label('Domain')
                    ->icon('heroicon-m-globe-alt')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->description(fn (Domain $record): ?string => $record->is_primary ? '★ Primary Storefront Address' : null),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->state(fn (Domain $record): string => $record->isCustom() ? 'Custom Domain' : 'System Subdomain')
                    ->color(fn (string $state): string => $state === 'Custom Domain' ? 'info' : 'gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(fn (Domain $record): string => $record->isHealthy() ? 'Connected' : 'Pending Verification')
                    ->color(fn (string $state): string => $state === 'Connected' ? 'success' : 'warning')
                    ->icon(fn (string $state): string => $state === 'Connected' ? 'heroicon-m-check-circle' : 'heroicon-m-clock'),

                TextColumn::make('dns_provider')
                    ->label('DNS Routing')
                    ->badge()
                    ->state(function (Domain $record): string {
                        if (! $record->isCustom()) {
                            return 'AffanHub Managed';
                        }

                        $isCf = $record->cloudflare_detected || app(DnsVerificationService::class)->isCloudflareManaged($record->domain);

                        return $isCf ? 'Cloudflare DNS' : 'External DNS';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Cloudflare DNS' => 'warning',
                        'AffanHub Managed' => 'success',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Cloudflare DNS' => 'heroicon-m-bolt',
                        'AffanHub Managed' => 'heroicon-m-shield-check',
                        default => 'heroicon-m-server',
                    }),

                TextColumn::make('created_at')
                    ->label('Added On')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                // 1-Click Cloudflare Connect
                Action::make('cloudflare_connect')
                    ->label('1-Click Cloudflare')
                    ->icon('heroicon-m-bolt')
                    ->color('warning')
                    ->button()
                    ->size('sm')
                    ->visible(fn (Domain $record): bool => $record->isCustom()
                        && ! $record->isHealthy()
                        && ($record->cloudflare_detected || app(DnsVerificationService::class)->isCloudflareManaged($record->domain))
                    )
                    ->url(fn (Domain $record): string => route('merchant.cloudflare.connect', ['domain' => $record->id])),

                // DNS Instructions Modal
                Action::make('dns_instructions')
                    ->label('DNS Records')
                    ->icon('heroicon-m-information-circle')
                    ->color('info')
                    ->modalHeading(fn (Domain $record): string => "DNS Setup for {$record->domain}")
                    ->modalWidth('lg')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn (Domain $record) => view('filament.merchant.components.domain-dns-modal', [
                        'domain' => $record,
                        'fallbackCname' => config('services.cloudflare.fallback_cname', 'sites.affanhub.com'),
                        'isCloudflare' => $record->cloudflare_detected || app(DnsVerificationService::class)->isCloudflareManaged($record->domain),
                    ]))
                    ->visible(fn (Domain $record): bool => $record->isCustom() && ! $record->isHealthy()),

                // Verify Connection
                Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-m-arrow-path')
                    ->color('primary')
                    ->visible(fn (Domain $record): bool => $record->isCustom() && ! $record->isHealthy())
                    ->action(function (Domain $record, CustomDomainOnboardingService $service): void {
                        try {
                            $success = $service->verifyAndProvision($record);
                            if ($success) {
                                ActivityLogger::log('domain_verified', "Verified custom domain {$record->domain}", ['domain' => $record->domain]);
                                Notification::make()
                                    ->title('Domain Connected!')
                                    ->body('Your custom domain is verified and routing is active.')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Verification In Progress')
                                    ->body($record->last_verification_message ?? 'DNS record not detected yet. DNS changes can take a few minutes to propagate.')
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Verification Error')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                // Make Primary
                Action::make('make_primary')
                    ->label('Make Primary')
                    ->icon('heroicon-m-star')
                    ->color('success')
                    ->visible(fn (Domain $record): bool => ! $record->is_primary && $record->isHealthy())
                    ->requiresConfirmation()
                    ->modalHeading('Set as Primary Domain')
                    ->modalDescription(fn (Domain $record): string => "Set {$record->domain} as the primary storefront address for your customers?")
                    ->action(function (Domain $record): void {
                        $tenant = Filament::getTenant();
                        Domain::where('tenant_id', $tenant->id)
                            ->where('is_primary', true)
                            ->update(['is_primary' => false]);

                        $record->update(['is_primary' => true]);
                        ActivityLogger::log('domain_primary_changed', "Set custom domain {$record->domain} as primary", ['domain' => $record->domain]);

                        Notification::make()
                            ->title('Primary Domain Updated')
                            ->body("{$record->domain} is now the primary domain for your store.")
                            ->success()
                            ->send();
                    }),

                // Remove Domain
                Action::make('delete')
                    ->label('Remove')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->visible(fn (Domain $record): bool => $record->isCustom())
                    ->requiresConfirmation()
                    ->modalHeading(fn (Domain $record): string => "Disconnect {$record->domain}")
                    ->modalDescription('Are you sure you want to remove this domain? Visitors will no longer be able to reach your storefront using this address.')
                    ->action(function (Domain $record, CustomDomainOnboardingService $service): void {
                        try {
                            $domainName = $record->domain;
                            $service->removeDomain($record);
                            ActivityLogger::log('domain_removed', "Removed custom domain {$domainName}", ['domain' => $domainName]);

                            Notification::make()
                                ->title('Domain Removed')
                                ->body('The custom domain was removed successfully.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error Removing Domain')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->emptyStateHeading('No Domains Found')
            ->emptyStateDescription('Your store does not have any domains configured yet.')
            ->emptyStateIcon('heroicon-o-globe-alt')
            ->defaultSort('created_at', 'desc');
    }
}
