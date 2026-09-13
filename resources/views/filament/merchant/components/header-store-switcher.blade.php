@php
    $user = filament()->auth()->user();
    $currentTenant = filament()->getTenant();

    if (! $user || ! $currentTenant) {
        return;
    }

    $allStores = $user->stores ?? collect();
    $canCreateMore = method_exists($user, 'canCreateMoreStores') ? $user->canCreateMoreStores() : true;
    
    // Resolve tenant registration URL via named route
    $registrationUrl = route('filament.merchant.tenant.registration');
    
    // Resolve active primary domain with proper port handling for local dev & production
    $activeDomain = $currentTenant->domains->firstWhere('is_primary', true)?->domain 
        ?? $currentTenant->domains->first()?->domain;

    $appPort = request()->getPort();
    $portSuffix = ($appPort && ! in_array($appPort, [80, 443])) ? (':' . $appPort) : '';
    $scheme = request()->getScheme() ?: 'http';
    $storefrontUrl = $activeDomain ? ($scheme . '://' . $activeDomain . $portSuffix) : null;
@endphp

<div style="display: inline-flex; align-items: center; margin-right: 0.75rem;">
    <x-filament::dropdown placement="bottom-end" teleport>
        <x-slot name="trigger">
            <button
                type="button"
                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.65rem; border-radius: 0.5rem; border: 1px solid rgba(156, 163, 175, 0.25); background: rgba(255, 255, 255, 0.95); cursor: pointer; text-align: left; transition: all 0.15s ease; outline: none; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);"
                class="hover:bg-gray-50 dark:bg-gray-900 dark:border-white/10 dark:hover:bg-white/5"
            >
                <div style="width: 24px; height: 24px; border-radius: 6px; background-color: rgba(245, 158, 11, 0.15); color: #d97706; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px; text-transform: uppercase; flex-shrink: 0;">
                    {{ strtoupper(substr($currentTenant->name, 0, 2)) }}
                </div>

                <div style="display: flex; flex-direction: column; max-width: 130px; line-height: 1.2; overflow: hidden; text-align: left;">
                    <span style="font-weight: 600; font-size: 12px; color: #111827; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" class="dark:text-white">
                        {{ $currentTenant->name }}
                    </span>
                    @if ($activeDomain)
                        <span style="font-size: 10px; color: #6b7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" class="dark:text-gray-400">
                            {{ $activeDomain }}
                        </span>
                    @endif
                </div>

                <svg style="width: 14px; height: 14px; color: #9ca3af; flex-shrink: 0; margin-left: 2px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                </svg>
            </button>
        </x-slot>

        <x-filament::dropdown.header
            color="gray"
            icon="heroicon-m-building-storefront"
        >
            Your Stores ({{ $allStores->count() }})
        </x-filament::dropdown.header>

        <x-filament::dropdown.list>
            @foreach ($allStores as $store)
                @php
                    $isCurrent = $store->getKey() === $currentTenant->getKey();
                    $storeUrl = filament()->getUrl($store);
                    $sDomain = $store->domains->firstWhere('is_primary', true)?->domain ?? $store->domains->first()?->domain;
                @endphp

                <x-filament::dropdown.list.item
                    :href="$storeUrl"
                    tag="a"
                    :color="$isCurrent ? 'amber' : 'gray'"
                    icon="heroicon-m-building-storefront"
                    class="{{ $isCurrent ? 'bg-amber-500/10 font-semibold' : '' }}"
                >
                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <div style="display: flex; flex-direction: column; text-align: left;">
                            <span class="text-sm {{ $isCurrent ? 'text-amber-700 dark:text-amber-400 font-bold' : 'text-gray-700 dark:text-gray-200' }}">
                                {{ $store->name }}
                            </span>
                            @if ($sDomain)
                                <span class="text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ $sDomain }}
                                </span>
                            @endif
                        </div>

                        @if ($isCurrent)
                            <svg style="width: 16px; height: 16px; color: #d97706; margin-left: 8px; flex-shrink: 0;" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>

        <div class="border-t border-gray-200 dark:border-white/10"></div>

        <x-filament::dropdown.list>
            @if ($storefrontUrl)
                <x-filament::dropdown.list.item
                    :href="$storefrontUrl"
                    tag="a"
                    target="_blank"
                    icon="heroicon-m-arrow-top-right-on-square"
                    color="gray"
                >
                    Live Storefront
                </x-filament::dropdown.list.item>
            @endif

            @if ($canCreateMore && $registrationUrl)
                <x-filament::dropdown.list.item
                    :href="$registrationUrl"
                    tag="a"
                    icon="heroicon-m-plus"
                    color="amber"
                >
                    Create New Store
                </x-filament::dropdown.list.item>
            @endif
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
