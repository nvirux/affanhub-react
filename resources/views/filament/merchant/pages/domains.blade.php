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
                                    <div style="border-top: 1px solid #e5e7eb; padding-top: 1rem; display: flex; flex-direction: column; gap: 1rem;">
                                        <p style="font-size: 0.8125rem; color: #4b5563; line-height: 1.5; margin: 0;">
                                            To complete linking your domain, configure the following DNS records in your domain registrar (Namecheap, GoDaddy, Cloudflare, etc.).
                                        </p>

                                        {{-- DNS Records Grid --}}
                                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                                            
                                            {{-- TXT Record --}}
                                            <div style="background-color: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                                <span style="font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase;">1. Ownership Verification (TXT)</span>
                                                <div style="font-size: 0.8125rem; display: flex; flex-direction: column; gap: 0.25rem;">
                                                    <div><span style="color: #666666;">Type:</span> <strong style="color: #111111;">TXT</strong></div>
                                                    <div><span style="color: #666666;">Host:</span> <code style="background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">_affanhub-verify.{{ $domain->domain }}</code></div>
                                                    <div><span style="color: #666666;">Value:</span> <code style="background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; word-break: break-all;">{{ $domain->verification_token }}</code></div>
                                                </div>
                                            </div>

                                            {{-- CNAME/A Record --}}
                                            <div style="background-color: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                                <span style="font-size: 0.6875rem; font-weight: 700; color: #888888; text-transform: uppercase;">2. Point Routing (CNAME)</span>
                                                <div style="font-size: 0.8125rem; display: flex; flex-direction: column; gap: 0.25rem;">
                                                    <div><span style="color: #666666;">Type:</span> <strong style="color: #111111;">CNAME</strong></div>
                                                    <div><span style="color: #666666;">Host:</span> <code style="background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">@</code> (or root)</div>
                                                    <div><span style="color: #666666;">Value:</span> <code style="background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">cname.affanhub.com</code></div>
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
