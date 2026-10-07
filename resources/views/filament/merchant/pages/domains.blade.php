<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $hasCustomDomainFeature = $tenant?->hasFeature('custom_domain') ?? false;
    @endphp

    @if(! $hasCustomDomainFeature)
        <div class="p-6 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 flex items-center justify-center text-xl flex-shrink-0">
                    🔒
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Custom Domain Mapping is Locked</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                        Connect your own custom brand domain (e.g. <span class="font-semibold text-gray-800 dark:text-gray-200">yourstore.com</span>) to replace your default AffanHub subdomain. Upgrade to a Pro or Enterprise plan to unlock custom domains.
                    </p>
                </div>
            </div>
            <a href="{{ \App\Filament\Merchant\Pages\Billing::getUrl() }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition shadow-sm whitespace-nowrap">
                Upgrade Plan
            </a>
        </div>
    @endif

    <div class="space-y-6">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
