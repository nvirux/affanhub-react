<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Domain;
use App\Services\Audit\ActivityLogger;
use App\Services\Domain\CustomDomainOnboardingService;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class Domains extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Custom Domains';

    protected static ?string $navigationLabel = 'Domains';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.merchant.pages.domains';

    public ?string $newDomain = '';

    public function addDomain(CustomDomainOnboardingService $service): void
    {
        $tenant = Filament::getTenant();

        if (! $tenant->hasFeature('custom_domain')) {
            $required = method_exists($tenant, 'getFeatureUpgradeRequirement')
                ? $tenant->getFeatureUpgradeRequirement('custom_domain')
                : 'Pro or Enterprise Plan';

            Notification::make()
                ->title('Feature Locked')
                ->body("Custom domain mapping is not included in your current plan. Please upgrade to {$required} to connect your own domain.")
                ->danger()
                ->send();

            return;
        }

        $this->validate([
            'newDomain' => 'required|string|min:3',
        ]);

        try {
            $domain = $service->submitDomain($tenant, $this->newDomain);
            ActivityLogger::log('domain_added', "Added custom domain {$domain->domain}", ['domain' => $domain->domain]);
            $this->newDomain = '';

            Notification::make()
                ->title('Domain Added')
                ->body('Please point your DNS CNAME record to AffanHub to finish connecting.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Adding Domain')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function verifyDomain(int $id, CustomDomainOnboardingService $service): void
    {
        $domain = Domain::findOrFail($id);

        try {
            $success = $service->verifyAndProvision($domain);
            if ($success) {
                ActivityLogger::log('domain_verified', "Verified custom domain {$domain->domain}", ['domain' => $domain->domain]);
                Notification::make()
                    ->title('Domain Connected')
                    ->body('Your custom domain is verified and active.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Verification Pending')
                    ->body($domain->last_verification_message ?? 'DNS record not detected yet. DNS changes can take a few minutes to propagate.')
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
    }

    public function makePrimary(int $id): void
    {
        $domain = Domain::findOrFail($id);
        $tenant = Filament::getTenant();

        if (! $domain->isHealthy()) {
            Notification::make()
                ->title('Action Denied')
                ->body('Only verified domains can be set as primary.')
                ->danger()
                ->send();

            return;
        }

        Domain::where('tenant_id', $tenant->id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);

        $domain->update(['is_primary' => true]);
        ActivityLogger::log('domain_primary_changed', "Set custom domain {$domain->domain} as primary", ['domain' => $domain->domain]);

        Notification::make()
            ->title('Primary Domain Updated')
            ->body("{$domain->domain} is now the primary storefront address.")
            ->success()
            ->send();
    }

    public function deleteDomain(int $id, CustomDomainOnboardingService $service): void
    {
        $domain = Domain::findOrFail($id);

        try {
            $domainName = $domain->domain;
            $service->removeDomain($domain);
            ActivityLogger::log('domain_removed', "Removed custom domain {$domainName}", ['domain' => $domainName]);

            Notification::make()
                ->title('Domain Removed')
                ->body('The domain was disconnected successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Removing Domain')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
