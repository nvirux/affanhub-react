<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $hasCustomDomain = $tenant?->hasFeature('custom_domain') ?? false;
        $domains = $tenant?->domains()->orderByDesc('is_primary')->orderBy('created_at')->get() ?? collect();
        $primaryDomain = $domains->firstWhere('is_primary', true) ?? $domains->first();

        $appPort = request()->getPort();
        $portSuffix = ($appPort && ! in_array($appPort, [80, 443])) ? (':' . $appPort) : '';
        $scheme = request()->getScheme() ?: 'https';
        $fallbackCname = config('services.cloudflare.fallback_cname', 'sites.affanhub.com');
    @endphp

    <style>
        .domains-container {
            font-family: inherit;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 960px;
            width: 100%;
            box-sizing: border-box;
        }

        .domain-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            box-sizing: border-box;
        }

        .dark .domain-card {
            background-color: #111827;
            border-color: #1f2937;
        }

        .domain-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.55rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
        }

        .badge-live, .badge-connected {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .dark .badge-live, .dark .badge-connected {
            background-color: rgba(6, 95, 70, 0.25);
            color: #6ee7b7;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .badge-pending {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .dark .badge-pending {
            background-color: rgba(146, 64, 14, 0.25);
            color: #fcd34d;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .badge-primary {
            background-color: #fef3c7;
            color: #78350f;
            border: 1px solid #fcd34d;
        }

        .dark .badge-primary {
            background-color: rgba(180, 83, 9, 0.25);
            color: #fde68a;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .badge-type {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .dark .badge-type {
            background-color: #1e293b;
            color: #94a3b8;
            border-color: #334155;
        }

        .domain-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
            box-sizing: border-box;
            border: none;
        }

        .domain-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-primary {
            background-color: #d97706;
            color: #ffffff;
        }

        .btn-primary:hover:not(:disabled) {
            background-color: #b45309;
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover:not(:disabled) {
            background-color: #f8fafc;
        }

        .dark .btn-secondary {
            background-color: #1e293b;
            color: #e2e8f0;
            border-color: #334155;
        }

        .dark .btn-secondary:hover:not(:disabled) {
            background-color: #334155;
        }

        .btn-dark {
            background-color: #0f172a;
            color: #ffffff;
        }

        .btn-dark:hover:not(:disabled) {
            background-color: #1e293b;
        }

        .dark .btn-dark {
            background-color: #f8fafc;
            color: #0f172a;
        }

        .btn-danger {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .btn-danger:hover:not(:disabled) {
            background-color: #fee2e2;
        }

        .dark .btn-danger {
            background-color: rgba(185, 28, 28, 0.15);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .btn-cloudflare {
            background-color: #ea580c;
            color: #ffffff;
        }

        .btn-cloudflare:hover {
            background-color: #c2410c;
        }

        .domain-input {
            width: 100%;
            max-width: 360px;
            padding: 0.5rem 0.85rem;
            font-size: 0.875rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #ffffff;
            color: #0f172a;
            box-sizing: border-box;
            outline: none;
        }

        .domain-input:focus {
            border-color: #d97706;
            box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.15);
        }

        .dark .domain-input {
            background-color: #1e293b;
            color: #f8fafc;
            border-color: #334155;
        }

        .cname-grid {
            display: grid;
            grid-template-columns: 1fr;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            background-color: #f8fafc;
            margin: 0.75rem 0;
        }

        @media (min-width: 600px) {
            .cname-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .cname-cell {
            padding: 0.65rem 0.85rem;
            border-bottom: 1px solid #e2e8f0;
            box-sizing: border-box;
        }

        @media (min-width: 600px) {
            .cname-cell {
                border-bottom: none;
                border-right: 1px solid #e2e8f0;
            }
            .cname-cell:last-child {
                border-right: none;
            }
        }

        .dark .cname-grid {
            background-color: #1e293b;
            border-color: #334155;
        }

        .dark .cname-cell {
            border-color: #334155;
        }

        .cname-label {
            font-size: 0.6875rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
            margin-bottom: 0.25rem;
        }

        .dark .cname-label {
            color: #94a3b8;
        }

        .cname-value {
            font-family: monospace;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #0f172a;
        }

        .dark .cname-value {
            color: #f8fafc;
        }
    </style>

    <div class="domains-container">

        {{-- 1. Primary Storefront Address Banner --}}
        @if($primaryDomain)
            @php
                $storeUrl = $scheme . '://' . $primaryDomain->domain . $portSuffix;
            @endphp
            <div class="domain-card">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">
                                Primary Storefront Address
                            </span>
                            <span class="domain-badge badge-live">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #10b981;"></span>
                                Live
                            </span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <a href="{{ $storeUrl }}" target="_blank" rel="noopener" style="font-size: 1.15rem; font-weight: 800; color: #0f172a; text-decoration: none;">
                                {{ $primaryDomain->domain }}
                            </a>
                            <span style="font-size: 0.75rem; color: #64748b;">🔒 SSL Active</span>
                        </div>

                        <span style="font-size: 0.8125rem; color: #64748b;">
                            Visitors typing this address will land directly on your storefront catalog.
                        </span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <button
                            type="button"
                            x-data="{ copied: false }"
                            @click="navigator.clipboard.writeText('{{ $storeUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="domain-btn btn-secondary"
                        >
                            <span x-show="!copied">Copy Link</span>
                            <span x-show="copied" style="color: #10b981;">Copied!</span>
                        </button>

                        <a href="{{ $storeUrl }}" target="_blank" rel="noopener" class="domain-btn btn-dark">
                            <span>Visit Store</span>
                            <span>↗</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- 2. Connect Custom Domain Card OR Feature Locked Banner --}}
        @if($hasCustomDomain)
            <div class="domain-card">
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div>
                        <h3 style="font-size: 0.9375rem; font-weight: 700; color: #0f172a; margin: 0;">Connect a Custom Domain</h3>
                        <p style="font-size: 0.8125rem; color: #64748b; margin: 0.25rem 0 0 0;">
                            Enter a custom domain you own (e.g. <span style="font-weight: 600; color: #334155;">mybrand.com</span> or <span style="font-weight: 600; color: #334155;">shop.mybrand.com</span>).
                        </p>
                    </div>

                    <form wire:submit.prevent="addDomain" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                        <input
                            type="text"
                            wire:model="newDomain"
                            placeholder="e.g. brandstore.com"
                            class="domain-input"
                        />
                        <button type="submit" wire:loading.attr="disabled" class="domain-btn btn-primary">
                            <span wire:loading.remove wire:target="addDomain">Connect Domain</span>
                            <span wire:loading wire:target="addDomain">Connecting...</span>
                        </button>
                    </form>
                    @error('newDomain')
                        <span style="font-size: 0.75rem; color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @else
            <div class="domain-card" style="border-left: 4px solid #d97706;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-size: 0.9375rem; font-weight: 700; color: #0f172a;">
                            🔒 Custom Domain Mapping
                        </span>
                        <span style="font-size: 0.8125rem; color: #64748b;">
                            Connect your own brand domain name to replace your default AffanHub address. Upgrade to a Pro or Enterprise plan to unlock custom domains.
                        </span>
                    </div>
                    <a href="{{ \App\Filament\Merchant\Pages\Billing::getUrl() }}" class="domain-btn btn-primary">
                        Upgrade Plan
                    </a>
                </div>
            </div>
        @endif

        {{-- 3. Configured Domains Section --}}
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; padding: 0 0.25rem;">
                Configured Domains ({{ $domains->count() }})
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @forelse($domains as $domain)
                    @php
                        $isCustom = $domain->isCustom();
                        $isHealthy = $domain->isHealthy();
                        $isPrimary = (bool) $domain->is_primary;
                        $isCloudflare = $domain->cloudflare_detected || app(\App\Services\Domain\DnsVerificationService::class)->isCloudflareManaged($domain->domain);
                    @endphp

                    <div class="domain-card" style="display: flex; flex-direction: column; gap: 1rem;">
                        {{-- Row 1: Header --}}
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                                <span style="font-size: 1rem; font-weight: 700; color: #0f172a;">
                                    {{ $domain->domain }}
                                </span>

                                @if($isPrimary)
                                    <span class="domain-badge badge-primary">Primary</span>
                                @endif

                                @if($isCustom)
                                    <span class="domain-badge badge-type">Custom Domain</span>
                                @else
                                    <span class="domain-badge badge-type">System Subdomain</span>
                                @endif

                                @if($isHealthy)
                                    <span class="domain-badge badge-connected">
                                        <span style="width: 5px; height: 5px; border-radius: 50%; background-color: #10b981;"></span>
                                        Connected
                                    </span>
                                @else
                                    <span class="domain-badge badge-pending">
                                        <span style="width: 5px; height: 5px; border-radius: 50%; background-color: #f59e0b;"></span>
                                        Pending DNS Setup
                                    </span>
                                @endif
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @if(! $isPrimary && $isHealthy)
                                    <button
                                        type="button"
                                        wire:click="makePrimary({{ $domain->id }})"
                                        wire:loading.attr="disabled"
                                        class="domain-btn btn-secondary"
                                    >
                                        Set as Primary
                                    </button>
                                @endif

                                @if($isCustom)
                                    <button
                                        type="button"
                                        wire:click="deleteDomain({{ $domain->id }})"
                                        wire:confirm="Are you sure you want to disconnect {{ $domain->domain }}? Visitors will no longer reach your storefront with this address."
                                        class="domain-btn btn-danger"
                                    >
                                        Remove
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Row 2: Instructions (only for pending custom domains) --}}
                        @if($isCustom && ! $isHealthy)
                            <div style="border-top: 1px solid #f1f5f9; padding-top: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
                                @if($isCloudflare)
                                    <div style="background-color: #fff7ed; border: 1px solid #fed7aa; border-radius: 10px; padding: 0.85rem 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                                        <div style="display: flex; flex-direction: column; gap: 0.15rem;">
                                            <span style="font-size: 0.8125rem; font-weight: 700; color: #9a3412;">
                                                ⚡ Cloudflare DNS Detected
                                            </span>
                                            <span style="font-size: 0.75rem; color: #7c2d12;">
                                                This domain is managed on Cloudflare. Connect your account in 1 click to set up routing and SSL automatically.
                                            </span>
                                        </div>
                                        <a href="{{ route('merchant.cloudflare.connect', ['domain' => $domain->id]) }}" class="domain-btn btn-cloudflare">
                                            <span>Continue with Cloudflare</span>
                                            <span>→</span>
                                        </a>
                                    </div>
                                @endif

                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <span style="font-size: 0.75rem; color: #64748b;">
                                        Add this <strong>CNAME</strong> record at your domain provider (e.g. Cloudflare, Namecheap, GoDaddy):
                                    </span>

                                    <div class="cname-grid">
                                        <div class="cname-cell">
                                            <span class="cname-label">Type</span>
                                            <span class="cname-value" style="color: #d97706;">CNAME</span>
                                        </div>
                                        <div class="cname-cell">
                                            <span class="cname-label">Host / Name</span>
                                            <span class="cname-value">@ <span style="font-size: 0.6875rem; color: #64748b; font-weight: normal;">(or root)</span></span>
                                        </div>
                                        <div class="cname-cell" x-data="{ copied: false }" style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <span class="cname-label">Points To</span>
                                                <span class="cname-value" style="color: #d97706;">{{ $fallbackCname }}</span>
                                            </div>
                                            <button
                                                type="button"
                                                @click="navigator.clipboard.writeText('{{ $fallbackCname }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                                style="border: none; background: transparent; cursor: pointer; font-size: 0.6875rem; font-weight: 600; text-decoration: underline; color: #64748b;"
                                            >
                                                <span x-show="!copied">Copy</span>
                                                <span x-show="copied" style="color: #10b981;">Copied!</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                    <button
                                        type="button"
                                        wire:click="verifyDomain({{ $domain->id }})"
                                        wire:loading.attr="disabled"
                                        class="domain-btn btn-dark"
                                    >
                                        <span wire:loading.remove wire:target="verifyDomain({{ $domain->id }})">Verify DNS Connection</span>
                                        <span wire:loading wire:target="verifyDomain({{ $domain->id }})">Checking DNS...</span>
                                    </button>

                                    @if($domain->verification_error)
                                        <span style="font-size: 0.75rem; color: #b45309;">
                                            ⚠️ {{ $domain->last_verification_message ?? $domain->verification_error }}
                                        </span>
                                    @else
                                        <span style="font-size: 0.6875rem; color: #64748b;">
                                            SSL certificate automatically activates once DNS propagates.
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="domain-card" style="text-align: center; color: #64748b; padding: 2rem;">
                        No domains configured yet.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-filament-panels::page>
