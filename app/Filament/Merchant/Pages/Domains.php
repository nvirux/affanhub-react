<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Domain;
use App\Services\Domain\CustomDomainOnboardingService;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use UnitEnum;
use BackedEnum;

class Domains extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Custom Domains';

    protected static ?string $navigationLabel = 'Domains';

    protected static ?int $navigationSort = 9;

    protected string $view = 'filament.merchant.pages.domains';

    public ?string $newDomain = '';

    public function addDomain(CustomDomainOnboardingService $service)
    {
        $tenant = Filament::getTenant();
        
        // Entitlement Check: custom_domain
        if (! $tenant->hasFeature('custom_domain')) {
            Notification::make()
                ->title('Feature Locked')
                ->body('Custom domain mapping is not available on your current plan. Please upgrade to the Pro plan.')
                ->danger()
                ->send();
            return;
        }

        $this->validate([
            'newDomain' => 'required|string|min:3',
        ]);

        try {
            $domain = $service->submitDomain($tenant, $this->newDomain);
            \App\Services\Audit\ActivityLogger::log('domain_added', "Added custom domain {$domain->domain}", ['domain' => $domain->domain]);
            $this->newDomain = '';
            
            Notification::make()
                ->title('Domain Added Successfully')
                ->body('Please configure your DNS records as shown below and verify ownership.')
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

    public function verifyDomain(int $id, CustomDomainOnboardingService $service)
    {
        $domain = Domain::findOrFail($id);
        
        try {
            $success = $service->verifyAndProvision($domain);
            if ($success) {
                \App\Services\Audit\ActivityLogger::log('domain_verified', "Verified custom domain {$domain->domain}", ['domain' => $domain->domain]);
                Notification::make()
                    ->title('Domain Connected!')
                    ->body('Your custom domain is verified and routing is active.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Verification Failed')
                    ->body($domain->last_verification_message ?? 'TXT verification token not found. Please wait a few minutes and try again.')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Verifying Domain')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function makePrimary(int $id)
    {
        $domain = Domain::findOrFail($id);
        $tenant = Filament::getTenant();

        // Must be verified
        if (! $domain->isHealthy()) {
            Notification::make()
                ->title('Action Denied')
                ->body('Only active and verified domains can be set as primary.')
                ->danger()
                ->send();
            return;
        }

        // Reset other domains to is_primary = false
        Domain::where('tenant_id', $tenant->id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);

        $domain->update(['is_primary' => true]);
        \App\Services\Audit\ActivityLogger::log('domain_primary_changed', "Set custom domain {$domain->domain} as primary", ['domain' => $domain->domain]);

        Notification::make()
            ->title('Primary Domain Updated')
            ->body("{$domain->domain} is now the primary domain for your store.")
            ->success()
            ->send();
    }

    public function deleteDomain(int $id, CustomDomainOnboardingService $service)
    {
        $domain = Domain::findOrFail($id);

        try {
            $domainName = $domain->domain;
            $service->removeDomain($domain);
            \App\Services\Audit\ActivityLogger::log('domain_removed', "Removed custom domain {$domainName}", ['domain' => $domainName]);
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
    }
}
