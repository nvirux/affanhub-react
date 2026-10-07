<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $hasCustomDomainFeature = $tenant->hasFeature('custom_domain');
        $domains = $tenant->domains;
    @endphp

    <div style="font-family: inherit; display: flex; flex-direction: column; gap: 2rem;">
        
        @if(!$hasCustomDomainFeature)
            {{-- Feature Locked Screen --}}
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 3rem; text-align: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); max-width: 600px; margin: 2rem auto; display: flex; flex-direction: column; align-items: center; gap: 1.5rem;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background-color: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 1.5rem;">
                    🔒
                </div>
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0;">Custom Domain Mapping is Locked</h2>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem; line-height: 1.5;">
                        Connect your own custom brand domain name (like <span style="font-weight: 600; color: #374151;">yourstore.com</span>) to replace your default AffanHub subdomain. Upgrade to a Pro or Enterprise plan to unlock custom domains.
                    </p>
                </div>
                <a href="{{ \App\Filament\Merchant\Pages\Billing::getUrl() }}" style="display: inline-flex; align-items: center; justify-content: center; background-color: #f59e0b; color: white; font-weight: 700; font-size: 0.875rem; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none; transition: background-color 0.2s; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.15);">
                    Upgrade Plan
                </a>
            </div>
        @else
            {{-- Flash Notifications --}}
            @if(session('success'))
                <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; color: #065f46; font-size: 0.875rem; font-weight: 600;">
                    <svg style="width: 20px; height: 20px; color: #10b981; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; color: #991b1b; font-size: 0.875rem; font-weight: 600;">
                    <svg style="width: 20px; height: 20px; color: #ef4444; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Main Custom Domain Dashboard --}}
            <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
                
                {{-- Form: Add New Domain --}}
                <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);">
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Connect a Custom Domain</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 1.5rem;">Enter the domain you want to use for your storefront</p>
                    
                    <form wire:submit.prevent="addDomain" style="display: flex; gap: 1rem; align-items: center; max-width: 600px;">
                        <div style="flex-grow: 1; position: relative;">
                            <input 
                                type="text" 
                                wire:model="newDomain" 
                                placeholder="e.g. mybrandstore.com" 
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none; transition: border-color 0.2s;"
                            />
                        </div>
                        <button type="submit" wire:loading.attr="disabled" style="background-color: #f59e0b; color: white; font-weight: 700; font-size: 0.875rem; padding: 0.75rem 1.5rem; border: none; border-radius: 12px; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.15);">
                            <span wire:loading.remove wire:target="addDomain">Add Domain</span>
                            <span wire:loading wire:target="addDomain">Adding...</span>
                        </button>
                    </form>
                    @error('newDomain') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
                </div>

                {{-- List: Store Domains --}}
                <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Connected Domains</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($domains as $domain)
                            @php
                                $isCustom = $domain->isCustom();
                                $isHealthy = $domain->isHealthy();
                                $isPrimary = $domain->is_primary;
                            @endphp
                            
                            <div style="border: 1px solid #f3f4f6; border-radius: 12px; padding: 1.25rem; background-color: #f9fafb; display: flex; flex-direction: column; gap: 1.25rem;">
                                
                                {{-- Header info --}}
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <span style="font-size: 1.125rem; font-weight: 700; color: #111827;">{{ $domain->domain }}</span>
                                        
                                        <span style="font-size: 0.6875rem; font-weight: 600; padding: 0.125rem 0.5rem; border-radius: 9999px; background-color: #e5e7eb; color: #4b5563;">
                                            {{ $isCustom ? 'Custom' : 'System Default' }}
                                        </span>

                                        @if($isPrimary)
                                            <span style="font-size: 0.6875rem; font-weight: 600; padding: 0.125rem 0.5rem; border-radius: 9999px; background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a;">
                                                Primary Domain
                                            </span>
                                        @endif
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        {{-- Health Badge --}}
                                        @if($isHealthy)
                                            <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; color: #065f46; background-color: #ecfdf5;">
                                                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #10b981;"></span>
                                                Connected
                                            </span>
                                        @else
                                            <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; color: #b45309; background-color: #fffbeb;">
                                                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #f59e0b;"></span>
                                                Pending Verification
                                            </span>
                                        @endif

                                        {{-- Actions --}}
                                        <div style="display: flex; gap: 0.5rem;">
                                            @if(!$isPrimary && $isHealthy)
                                                <button wire:click="makePrimary({{ $domain->id }})" wire:loading.attr="disabled" style="padding: 0.375rem 0.75rem; font-size: 0.75rem; font-weight: 600; border: 1px solid #d1d5db; border-radius: 8px; background-color: white; cursor: pointer; color: #374151; transition: background-color 0.2s;">
                                                    <span wire:loading.remove wire:target="makePrimary({{ $domain->id }})">Make Primary</span>
                                                    <span wire:loading wire:target="makePrimary({{ $domain->id }})">Updating...</span>
                                                </button>
                                            @endif

                                            @if($isCustom)
                                                <button wire:click="deleteDomain({{ $domain->id }})" style="padding: 0.375rem 0.75rem; font-size: 0.75rem; font-weight: 600; border: 1px solid #fca5a5; border-radius: 8px; background-color: #fef2f2; cursor: pointer; color: #b91c1c; transition: background-color 0.2s;">
                                                    Remove
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Verification Instructions (Only for pending custom domains) --}}
                                @if($isCustom && !$isHealthy)
                                    @php
                                        $isCloudflareDomain = $domain->cloudflare_detected || app(\App\Services\Domain\DnsVerificationService::class)->isCloudflareManaged($domain->domain);
                                    @endphp

                                    <div style="border-top: 1px solid #e5e7eb; padding-top: 1.25rem; display: flex; flex-direction: column; gap: 1.25rem;">
                                        
                                        @if($isCloudflareDomain)
                                            {{-- CLOUDFLARE DETECTED: Show 1-Click Action --}}
                                            <div style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 1.5px solid #fed7aa; border-radius: 14px; padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.25rem; box-shadow: 0 4px 12px rgba(234, 88, 12, 0.08);">
                                                <div style="display: flex; align-items: center; gap: 1rem; max-width: 600px;">
                                                    <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; box-shadow: 0 4px 10px rgba(249, 115, 22, 0.35);">
                                                        <svg style="width: 28px; height: 28px;" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div style="font-size: 1rem; font-weight: 800; color: #9a3412; display: flex; align-items: center; gap: 0.5rem;">
                                                            ⚡ Cloudflare DNS Detected
                                                            <span style="font-size: 0.6875rem; background-color: #ea580c; color: white; padding: 2px 8px; border-radius: 9999px; font-weight: 700;">1-Click Setup</span>
                                                        </div>
                                                        <p style="font-size: 0.8125rem; color: #7c2d12; margin: 0.25rem 0 0 0; line-height: 1.4;">
                                                            We detected that <strong>{{ $domain->domain }}</strong> is managed on Cloudflare. Connect your account to automatically configure DNS routing and free SSL in seconds.
                                                        </p>
                                                    </div>
                                                </div>
                                                <a href="{{ route('merchant.cloudflare.connect', ['domain' => $domain->id]) }}" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; font-weight: 700; font-size: 0.875rem; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 10px rgba(234, 88, 12, 0.35);">
                                                    <span>Continue with Cloudflare</span>
                                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>
                                            </div>

                                            {{-- Collapsible manual option for Cloudflare domains if desired --}}
                                            <details style="font-size: 0.8125rem; color: #6b7280;">
                                                <summary style="cursor: pointer; font-weight: 600; color: #4b5563;">Prefer to configure Cloudflare DNS manually?</summary>
                                                <div style="margin-top: 0.75rem; background-color: white; border: 1px solid #e5e7eb; border-radius: 10px; padding: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                                    <p style="margin: 0; color: #4b5563;">Add a CNAME record in your Cloudflare dashboard:</p>
                                                    <div><strong>Type:</strong> CNAME &nbsp;|&nbsp; <strong>Name:</strong> @ (or www) &nbsp;|&nbsp; <strong>Target:</strong> <code>{{ config('services.cloudflare.fallback_cname', 'sites.affanhub.com') }}</code> &nbsp;|&nbsp; <strong>Proxy:</strong> Proxied (Orange)</div>
                                                </div>
                                            </details>

                                        @else
                                            {{-- NON-CLOUDFLARE DOMAIN: Show Standard CNAME Instruction --}}
                                            <div>
                                                <div style="font-size: 0.9375rem; font-weight: 700; color: #111827;">Configure Your DNS Record</div>
                                                <p style="font-size: 0.8125rem; color: #4b5563; margin-top: 0.25rem; margin-bottom: 0.75rem;">
                                                    Log in to your domain provider (Namecheap, GoDaddy, Google, etc.) and add the following <strong>CNAME</strong> record. Free SSL and routing will activate automatically.
                                                </p>
                                            </div>

                                            <div style="background-color: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                                <div>
                                                    <span style="font-size: 0.6875rem; font-weight: 700; color: #9ca3af; text-transform: uppercase;">Record Type</span>
                                                    <div style="font-size: 0.9375rem; font-weight: 700; color: #111827; margin-top: 0.25rem;">CNAME</div>
                                                </div>
                                                <div>
                                                    <span style="font-size: 0.6875rem; font-weight: 700; color: #9ca3af; text-transform: uppercase;">Host / Name</span>
                                                    <div style="font-size: 0.9375rem; font-weight: 700; color: #111827; margin-top: 0.25rem;">
                                                        <code style="background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px;">@</code> (or root)
                                                    </div>
                                                </div>
                                                <div>
                                                    <span style="font-size: 0.6875rem; font-weight: 700; color: #9ca3af; text-transform: uppercase;">Points To / Target</span>
                                                    <div style="font-size: 0.9375rem; font-weight: 700; color: #ea580c; margin-top: 0.25rem;">
                                                        <code style="background-color: #fff7ed; border: 1px solid #fed7aa; padding: 2px 8px; border-radius: 4px; font-weight: 700;">{{ config('services.cloudflare.fallback_cname', 'sites.affanhub.com') }}</code>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Verify Button & Feedback --}}
                                            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                                                <button 
                                                    wire:click="verifyDomain({{ $domain->id }})" 
                                                    wire:loading.attr="disabled"
                                                    style="background-color: #111827; color: white; font-weight: 700; font-size: 0.8125rem; padding: 0.5rem 1.25rem; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.2s;"
                                                >
                                                    <span wire:loading.remove wire:target="verifyDomain({{ $domain->id }})">Verify Connection</span>
                                                    <span wire:loading wire:target="verifyDomain({{ $domain->id }})">Verifying...</span>
                                                </button>

                                                @if($domain->verification_error)
                                                    <span style="font-size: 0.75rem; color: #b45309; font-weight: 500;">
                                                        ⚠️ Last attempt: {{ $domain->last_verification_message ?? $domain->verification_error }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endif
    </div>
</x-filament-panels::page>
