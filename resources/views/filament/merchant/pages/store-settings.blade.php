<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $hasCustomBranding = $tenant->hasFeature('custom_branding');
    @endphp

    <style>
        .settings-container {
            font-family: inherit;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        .settings-tabs-wrapper {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            padding: 0.625rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            width: 100%;
            box-sizing: border-box;
        }
        @media (min-width: 640px) {
            .settings-tabs-wrapper {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.625rem 0.875rem;
                overflow-x: auto;
            }
        }
        .settings-tab-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            padding: 0.625rem 0.5rem;
            font-size: 0.8125rem;
            font-weight: 700;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            user-select: none;
        }
        .settings-tab-btn:hover:not(.is-active) {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }
        .settings-tab-btn.is-active {
            background-color: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.15);
        }
        @media (min-width: 640px) {
            .settings-tab-btn {
                width: auto;
                font-size: 0.875rem;
                padding: 0.625rem 1.25rem;
                white-space: nowrap;
            }
        }
        .settings-card {
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
        }
        @media (min-width: 640px) {
            .settings-card {
                padding: 2rem;
                gap: 1.5rem;
            }
        }
        .settings-grid-2 {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            box-sizing: border-box;
        }
        @media (min-width: 640px) {
            .settings-grid-2 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
            }
            .settings-col-span-2 {
                grid-column: span 2;
            }
        }
        .settings-presets-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            width: 100%;
            box-sizing: border-box;
        }
        @media (min-width: 480px) {
            .settings-presets-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.625rem;
            }
        }
        @media (min-width: 768px) {
            .settings-presets-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 0.75rem;
            }
        }
        .settings-color-preview-grid {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            box-sizing: border-box;
        }
        @media (min-width: 768px) {
            .settings-color-preview-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
                align-items: start;
            }
        }
        .settings-save-bar {
            display: flex;
            flex-direction: column-reverse;
            gap: 0.75rem;
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            width: 100%;
            box-sizing: border-box;
        }
        @media (min-width: 640px) {
            .settings-save-bar {
                flex-direction: row;
                justify-content: flex-end;
                align-items: center;
                gap: 1rem;
                padding: 1rem 1.5rem;
            }
        }
        .settings-save-btn {
            width: 100%;
            background-color: #f59e0b;
            color: white;
            font-weight: 700;
            font-size: 0.9375rem;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.2s;
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);
            text-align: center;
            box-sizing: border-box;
        }
        @media (min-width: 640px) {
            .settings-save-btn {
                width: auto;
                font-size: 0.875rem;
                padding: 0.65rem 2rem;
            }
        }
        .settings-input {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            box-sizing: border-box;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            color: #111827;
            outline: none;
            transition: border-color 0.2s;
        }
        @media (min-width: 640px) {
            .settings-input {
                font-size: 0.875rem;
            }
        }
        .settings-input:focus {
            border-color: #111827;
        }
    </style>

    <div class="settings-container">
        
        {{-- Responsive 4-Tab Bar (2x2 tactile grid on mobile, horizontal pill row on tablet/desktop) --}}
        <div class="settings-tabs-wrapper">
            
            {{-- Tab 1: Profile --}}
            <button 
                type="button" 
                wire:click="setTab('profile')" 
                class="settings-tab-btn {{ $activeTab === 'profile' ? 'is-active' : '' }}"
            >
                <span>🏷️</span> Store Profile
            </button>

            {{-- Tab 2: Theme & Branding --}}
            <button 
                type="button" 
                wire:click="setTab('branding')" 
                class="settings-tab-btn {{ $activeTab === 'branding' ? 'is-active' : '' }}"
            >
                <span>🎨</span> Theme & Colors
            </button>

            {{-- Tab 3: Contact & Social --}}
            <button 
                type="button" 
                wire:click="setTab('contact')" 
                class="settings-tab-btn {{ $activeTab === 'contact' ? 'is-active' : '' }}"
            >
                <span>📞</span> Contact & Social
            </button>

            {{-- Tab 4: Live Support --}}
            <button 
                type="button" 
                wire:click="setTab('chat')" 
                class="settings-tab-btn {{ $activeTab === 'chat' ? 'is-active' : '' }}"
            >
                <span>💬</span> Live Support
            </button>

        </div>

        {{-- Form Container --}}
        <form wire:submit.prevent="saveSettings" style="display: flex; flex-direction: column; gap: 1.25rem; width: 100%; box-sizing: border-box;">

            {{-- ────────────────────────────────────────────────────────
                TAB 1: STORE PROFILE
                ──────────────────────────────────────────────────────── --}}
            @if ($activeTab === 'profile')
                <div class="settings-card">
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Store Profile</h3>
                        <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Update your public storefront name, logo, and brand tagline.</p>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                    <div style="display: flex; flex-direction: column; gap: 1.25rem; width: 100%;">
                        {{-- Store Logo Upload --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Logo</label>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; background: #f9fafb; padding: 1rem; border-radius: 12px; border: 1px dashed #d1d5db; width: 100%; box-sizing: border-box; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    @if ($logoPath)
                                        <div style="position: relative; width: 56px; height: 56px; border-radius: 8px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <img src="{{ asset('storage/' . $logoPath) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                            <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">Logo uploaded</span>
                                            <button type="button" wire:click="removeLogo" style="font-size: 0.75rem; font-weight: 700; color: #dc2626; text-align: left; background: none; border: none; cursor: pointer; padding: 0;">Remove Logo</button>
                                        </div>
                                    @elseif ($logo)
                                        <div style="position: relative; width: 56px; height: 56px; border-radius: 8px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <img src="{{ $logo->temporaryUrl() }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                            <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">Preview logo</span>
                                            <span style="font-size: 0.75rem; color: #6b7280;">Ready to save</span>
                                        </div>
                                    @else
                                        <div style="width: 56px; height: 56px; border-radius: 8px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 1.25rem; font-weight: 800; flex-shrink: 0;">
                                            {{ substr($name, 0, 1) ?: 'S' }}
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                            <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">No logo</span>
                                            <span style="font-size: 0.75rem; color: #6b7280;">PNG / JPG (max 1MB)</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <input type="file" id="logoInput" wire:model="logo" style="display: none;" accept="image/*">
                                    <label for="logoInput" style="background-color: white; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 700; color: #374151; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); white-space: nowrap;">
                                        Upload
                                    </label>
                                </div>
                            </div>
                            @error('logo') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Store Name --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Name <span style="color: #dc2626;">*</span></label>
                            <input 
                                type="text" 
                                wire:model="name" 
                                placeholder="e.g. My Premium Store" 
                                class="settings-input"
                            />
                            @error('name') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Store Subdomain (Read only) --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Subdomain (Unique ID)</label>
                            <input 
                                type="text" 
                                value="{{ $tenant->id }}.localhost:8000" 
                                disabled 
                                class="settings-input"
                                style="background-color: #f9fafb; color: #6b7280; cursor: not-allowed;"
                            />
                            <span style="font-size: 0.75rem; color: #9ca3af;">Subdomain is assigned automatically. Bind a custom domain under Settings > Domains.</span>
                        </div>

                        {{-- Description --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Description</label>
                            <textarea 
                                wire:model="description" 
                                rows="3" 
                                placeholder="Tell your customers about your shop, brand values, or catalog..." 
                                class="settings-input"
                                style="resize: vertical;"
                            ></textarea>
                            @error('description') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Dashboard Tagline --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Dashboard Subtitle / Tagline</label>
                            <input 
                                type="text" 
                                wire:model="dashboardSubtitle" 
                                placeholder="e.g. Fast & Reliable Telecom Services" 
                                maxlength="40"
                                class="settings-input"
                            />
                            <span style="font-size: 0.75rem; color: #9ca3af;">Displayed as a tagline below the greeting on your customers' mobile dashboard (max 40 characters).</span>
                            @error('dashboardSubtitle') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- ────────────────────────────────────────────────────────
                TAB 2: THEME & BRANDING
                ──────────────────────────────────────────────────────── --}}
            @if ($activeTab === 'branding')
                <div class="settings-card">
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Theme & Brand Colors</h3>
                        <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Customize your store's primary brand color and browser tab icon.</p>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                    {{-- Brand Color Presets (All Plans) --}}
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Curated Color Presets</label>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #059669; background: #ecfdf5; padding: 0.125rem 0.5rem; border-radius: 9999px;">Included on All Plans</span>
                        </div>
                        <div class="settings-presets-grid">
                            @foreach(\App\Services\Branding\ColorHelper::getPresets() as $key => $preset)
                                @php
                                    $isActive = strtoupper($preset['hex']) === strtoupper(\App\Services\Branding\ColorHelper::normalizeHex($primaryColor));
                                @endphp
                                <button 
                                    type="button" 
                                    wire:click="selectPreset('{{ $preset['hex'] }}')" 
                                    style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.625rem; border-radius: 10px; border: 1.5px solid {{ $isActive ? $preset['hex'] : '#e5e7eb' }}; background-color: {{ $isActive ? $preset['hex'] . '12' : '#ffffff' }}; cursor: pointer; transition: all 0.15s; text-align: left; width: 100%; box-sizing: border-box;"
                                >
                                    <span style="width: 18px; height: 18px; border-radius: 50%; background-color: {{ $preset['hex'] }}; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(0,0,0,0.15);">
                                        @if($isActive)
                                            <svg style="width: 10px; height: 10px; stroke: #ffffff; stroke-width: 3;" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </span>
                                    <span style="font-size: 0.75rem; font-weight: {{ $isActive ? '700' : '600' }}; color: {{ $isActive ? '#111827' : '#4b5563' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $preset['name'] }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Custom HEX Color Input & Live Preview --}}
                    <div class="settings-color-preview-grid">
                        
                        {{-- Custom Input --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Custom Brand Color (HEX)</label>
                                @if (! $hasCustomBranding)
                                    @php
                                        $requiredBrandingPlan = method_exists($tenant, 'getFeatureUpgradeRequirement')
                                            ? $tenant->getFeatureUpgradeRequirement('custom_branding')
                                            : 'Pro Plan';
                                    @endphp
                                    <span style="font-size: 0.75rem; font-weight: 700; color: #d97706; background: #fef3c7; padding: 0.125rem 0.5rem; border-radius: 9999px;">
                                        🔒 {{ $requiredBrandingPlan }}
                                    </span>
                                @endif
                            </div>

                            <div style="display: flex; gap: 0.5rem; align-items: center; width: 100%;">
                                <input 
                                    type="color" 
                                    wire:model.live="primaryColor" 
                                    value="{{ \App\Services\Branding\ColorHelper::isValidHex($primaryColor) ? \App\Services\Branding\ColorHelper::normalizeHex($primaryColor) : '#2563EB' }}"
                                    {{ ! $hasCustomBranding ? 'disabled' : '' }}
                                    style="width: 44px; height: 42px; border: 1px solid #d1d5db; border-radius: 8px; cursor: {{ $hasCustomBranding ? 'pointer' : 'not-allowed' }}; padding: 2px; background: white; flex-shrink: 0;"
                                />
                                <input 
                                    type="text" 
                                    wire:model.live="primaryColor" 
                                    placeholder="#2563EB" 
                                    maxlength="7"
                                    {{ ! $hasCustomBranding ? 'disabled' : '' }}
                                    class="settings-input"
                                    style="flex: 1; font-family: monospace; text-transform: uppercase; background-color: {{ $hasCustomBranding ? '#ffffff' : '#f9fafb' }}; cursor: {{ $hasCustomBranding ? 'text' : 'not-allowed' }};"
                                />
                            </div>

                            @if (! $hasCustomBranding)
                                <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 0.625rem 0.875rem; margin-top: 0.25rem; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap;">
                                    <span style="font-size: 0.75rem; color: #92400e; font-weight: 500;">
                                        Enter exact brand hex codes with {{ $requiredBrandingPlan }}.
                                    </span>
                                    <a href="{{ \App\Filament\Merchant\Pages\Billing::getUrl() }}" style="font-size: 0.75rem; font-weight: 700; color: #b45309; text-decoration: underline; white-space: nowrap;">
                                        Upgrade
                                    </a>
                                </div>
                            @else
                                <span style="font-size: 0.75rem; color: #6b7280;">Choose from presets above or enter your brand's 6-digit hex code. White and ultra-light colors are restricted for readability.</span>
                            @endif

                            @error('primaryColor') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Live Storefront Elements Preview --}}
                        @php
                            $safeHex = \App\Services\Branding\ColorHelper::isValidHex($primaryColor) ? \App\Services\Branding\ColorHelper::normalizeHex($primaryColor) : '#2563EB';
                            $contrastText = \App\Services\Branding\ColorHelper::getContrastForeground($safeHex);
                        @endphp
                        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem; width: 100%; box-sizing: border-box;">
                            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.025em;">Live Preview (Storefront Elements)</span>
                            <div style="display: flex; align-items: center; gap: 0.625rem; flex-wrap: wrap;">
                                <button type="button" style="background-color: {{ $safeHex }}; color: {{ $contrastText }}; font-weight: 700; font-size: 0.8125rem; padding: 0.5rem 0.875rem; border-radius: 8px; border: none; box-shadow: 0 2px 4px {{ $safeHex }}40; cursor: default; white-space: nowrap;">
                                    Deposit Funds
                                </button>
                                <span style="background-color: {{ $safeHex }}18; color: {{ $safeHex }}; font-weight: 700; font-size: 0.75rem; padding: 0.25rem 0.625rem; border-radius: 9999px; border: 1px solid {{ $safeHex }}35; white-space: nowrap;">
                                    Active Customer
                                </span>
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; font-weight: 700; color: {{ $safeHex }}; white-space: nowrap;">
                                    <svg style="width: 14px; height: 14px; fill: currentColor;" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                    Verified
                                </span>
                            </div>
                        </div>

                    </div>

                    {{-- Browser Favicon Upload --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem; border-top: 1px dashed #e5e7eb; padding-top: 1rem; width: 100%;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Browser Favicon (Tab Icon)</label>
                            @if (! $hasCustomBranding)
                                <span style="font-size: 0.75rem; font-weight: 700; color: #d97706; background: #fef3c7; padding: 0.125rem 0.5rem; border-radius: 9999px;">
                                    🔒 Pro Feature
                                </span>
                            @endif
                        </div>

                        @if (! $hasCustomBranding)
                            <div style="background-color: #f9fafb; border: 1px dashed #d1d5db; border-radius: 12px; padding: 1rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; flex-shrink: 0;">
                                        🌐
                                    </div>
                                    <div>
                                        <h5 style="font-size: 0.8125rem; font-weight: 700; color: #374151; margin: 0;">Default AffanHub Favicon</h5>
                                        <p style="font-size: 0.75rem; color: #6b7280; margin: 0.125rem 0 0 0;">Upload custom browser icon on Pro.</p>
                                    </div>
                                </div>
                                <a href="{{ \App\Filament\Merchant\Pages\Billing::getUrl() }}" style="background-color: #f59e0b; color: white; font-weight: 700; font-size: 0.75rem; padding: 0.5rem 0.875rem; border-radius: 8px; text-decoration: none; white-space: nowrap;">
                                    Upgrade
                                </a>
                            </div>
                        @else
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; background: #f9fafb; padding: 1rem; border-radius: 12px; border: 1px dashed #d1d5db; width: 100%; box-sizing: border-box; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if ($faviconPath)
                                        <div style="width: 44px; height: 44px; border-radius: 8px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <img src="{{ asset('storage/' . $faviconPath) }}" style="max-width: 32px; max-height: 32px; object-fit: contain;">
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                            <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Custom Favicon</span>
                                            <button type="button" wire:click="removeFavicon" style="font-size: 0.75rem; font-weight: 700; color: #dc2626; text-align: left; background: none; border: none; cursor: pointer; padding: 0;">Remove</button>
                                        </div>
                                    @elseif ($favicon)
                                        <div style="width: 44px; height: 44px; border-radius: 8px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <img src="{{ $favicon->temporaryUrl() }}" style="max-width: 32px; max-height: 32px; object-fit: contain;">
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                            <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Ready to save</span>
                                        </div>
                                    @else
                                        <div style="width: 44px; height: 44px; border-radius: 8px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 1.125rem; flex-shrink: 0;">
                                            🌐
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                            <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Default Icon</span>
                                            <span style="font-size: 0.75rem; color: #6b7280;">.png, .ico, .svg (max 512KB)</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <input type="file" id="faviconInput" wire:model="favicon" style="display: none;" accept=".png,.ico,.svg,image/png,image/x-icon,image/svg+xml">
                                    <label for="faviconInput" style="background-color: white; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.5rem 0.875rem; font-size: 0.75rem; font-weight: 700; color: #374151; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); white-space: nowrap;">
                                        Upload
                                    </label>
                                </div>
                            </div>
                            @error('favicon') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        @endif
                    </div>
                </div>
            @endif

            {{-- ────────────────────────────────────────────────────────
                TAB 3: CONTACT & SOCIAL
                ──────────────────────────────────────────────────────── --}}
            @if ($activeTab === 'contact')
                <div class="settings-card">
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Contact Details & Social Media</h3>
                        <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Direct customer support channels and social profile links shown on your storefront.</p>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                    <div class="settings-grid-2">
                        {{-- Contact Email --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Support Email Address</label>
                            <input 
                                type="email" 
                                wire:model="contactEmail" 
                                placeholder="e.g. support@mybrand.com" 
                                class="settings-input"
                            />
                            @error('contactEmail') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Contact Phone --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Support Phone Number</label>
                            <input 
                                type="text" 
                                wire:model="contactPhone" 
                                placeholder="e.g. +234 812 345 6789" 
                                class="settings-input"
                            />
                            @error('contactPhone') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Instagram --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Instagram Handle</label>
                            <div style="position: relative; display: flex; align-items: center; width: 100%;">
                                <span style="position: absolute; left: 1rem; color: #9ca3af; font-size: 0.875rem; pointer-events: none;">@</span>
                                <input 
                                    type="text" 
                                    wire:model="socialInstagram" 
                                    placeholder="mybrand" 
                                    class="settings-input"
                                    style="padding-left: 2rem;"
                                />
                            </div>
                            @error('socialInstagram') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Facebook --}}
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Facebook Page URL</label>
                            <input 
                                type="text" 
                                wire:model="socialFacebook" 
                                placeholder="https://facebook.com/mybrand" 
                                class="settings-input"
                            />
                            @error('socialFacebook') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- WhatsApp Community --}}
                        <div class="settings-col-span-2" style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">WhatsApp Channel / Group Link</label>
                            <input 
                                type="text" 
                                wire:model="socialWhatsapp" 
                                placeholder="e.g. https://chat.whatsapp.com/..." 
                                class="settings-input"
                            />
                            @error('socialWhatsapp') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- ────────────────────────────────────────────────────────
                TAB 4: LIVE SUPPORT WIDGETS
                ──────────────────────────────────────────────────────── --}}
            @if ($activeTab === 'chat')
                <div class="settings-card">
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Live Customer Support Widgets</h3>
                        <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Configure floating chat bubbles to assist your customers instantly on the storefront.</p>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                    <div style="display: flex; flex-direction: column; gap: 1.25rem; width: 100%;">
                        {{-- WhatsApp floating support (All Plans) --}}
                        <div style="display: flex; flex-direction: column; gap: 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 1.25rem; border-radius: 12px; width: 100%; box-sizing: border-box;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                                <div>
                                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #14532d; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                        <svg style="width: 18px; height: 18px; fill: #15803d; flex-shrink: 0;" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.963C16.588 2.02 14.12 1.002 11.5 1.002c-5.442 0-9.87 4.372-9.874 9.802-.001 1.769.471 3.498 1.362 5.031L2.002 21.5l5.727-1.492zm11.233-5.918c-.3-.15-1.771-.875-2.046-.975-.276-.1-.477-.15-.677.15-.2.3-.777.975-.951 1.175-.175.2-.35.225-.65.075-3.04-1.522-4.14-2.522-4.9-3.825-.2-.35-.022-.538.127-.687.135-.135.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.677-1.625-.926-2.225-.244-.589-.493-.51-.677-.52l-.578-.01c-.2 0-.525.075-.8.375-.275.3-1.05 1.025-1.05 2.5s1.07 2.9 1.22 3.1c.15.2 2.105 3.213 5.098 4.5 1.205.52 2.146.83 2.875 1.06.73.23 1.396.197 1.92.12.584-.087 1.771-.725 2.021-1.425.25-.7.25-1.3 1.75-1.425-.075-.125-.375-.275-.675-.425z"/></svg>
                                        WhatsApp Floating Support
                                    </h4>
                                    <p style="font-size: 0.8125rem; color: #166534; margin: 0.25rem 0 0 0;">Adds a direct WhatsApp chat button to the storefront.</p>
                                </div>
                                <div>
                                    <input type="checkbox" wire:model.live="whatsappChatEnabled" style="width: 1.25rem; height: 1.25rem; accent-color: #15803d; cursor: pointer;">
                                </div>
                            </div>
                            
                            @if ($whatsappChatEnabled)
                                <div class="settings-grid-2" style="margin-top: 0.5rem; border-top: 1px dashed #bbf7d0; padding-top: 1rem;">
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem; width: 100%;">
                                        <label style="font-size: 0.75rem; font-weight: 700; color: #14532d;">WhatsApp Phone Number</label>
                                        <input 
                                            type="text" 
                                            wire:model="whatsappChatPhone" 
                                            placeholder="e.g. 2348123456789" 
                                            class="settings-input"
                                            style="border-color: #86efac; background: white; color: #14532d;"
                                        />
                                        <span style="font-size: 0.7rem; color: #166534;">Include country code, no + signs or dashes (e.g. 234...).</span>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem; width: 100%;">
                                        <label style="font-size: 0.75rem; font-weight: 700; color: #14532d;">Pre-filled Greeting Message</label>
                                        <input 
                                            type="text" 
                                            wire:model="whatsappChatMessage" 
                                            placeholder="e.g. Hello, I need assistance." 
                                            class="settings-input"
                                            style="border-color: #86efac; background: white; color: #14532d;"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Tawk.to live chat widget (Pro / Enterprise) --}}
                        <div style="display: flex; flex-direction: column; gap: 1rem; background: #eff6ff; border: 1px solid #bfdbfe; padding: 1.25rem; border-radius: 12px; width: 100%; box-sizing: border-box;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;">
                                <div>
                                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #1e3a8a; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                        <svg style="width: 18px; height: 18px; fill: #2563eb; flex-shrink: 0;" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                                        Tawk.to Real-time Web Chat
                                    </h4>
                                    <p style="font-size: 0.8125rem; color: #1e40af; margin: 0.25rem 0 0 0;">Embed live customer support agent software directly into your web storefront.</p>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    @if (! $hasCustomBranding)
                                        <span style="font-size: 0.75rem; font-weight: 700; color: #1e40af; background: #dbeafe; padding: 0.125rem 0.5rem; border-radius: 9999px;">
                                            🔒 Pro Plan
                                        </span>
                                    @endif
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="tawkChatEnabled" 
                                        {{ ! $hasCustomBranding ? 'disabled' : '' }}
                                        style="width: 1.25rem; height: 1.25rem; accent-color: #2563eb; cursor: {{ $hasCustomBranding ? 'pointer' : 'not-allowed' }};"
                                    >
                                </div>
                            </div>
                            
                            @if (! $hasCustomBranding)
                                <div style="background-color: white; border: 1px solid #bfdbfe; border-radius: 10px; padding: 0.75rem 1rem; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;">
                                    <span style="font-size: 0.8125rem; color: #1e3a8a;">
                                        Tawk.to live chat is available on Pro & Enterprise plans.
                                    </span>
                                    <a href="{{ \App\Filament\Merchant\Pages\Billing::getUrl() }}" style="font-size: 0.75rem; font-weight: 700; color: #1d4ed8; text-decoration: underline; white-space: nowrap;">
                                        Upgrade Plan
                                    </a>
                                </div>
                            @elseif ($tawkChatEnabled)
                                <div class="settings-grid-2" style="margin-top: 0.5rem; border-top: 1px dashed #bfdbfe; padding-top: 1rem;">
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem; width: 100%;">
                                        <label style="font-size: 0.75rem; font-weight: 700; color: #1e3a8a;">Tawk.to Property ID</label>
                                        <input 
                                            type="text" 
                                            wire:model="tawkPropertyId" 
                                            placeholder="e.g. 64b8d78994af5e1234567890" 
                                            class="settings-input"
                                            style="border-color: #93c5fd; background: white; color: #1e3a8a;"
                                        />
                                        <span style="font-size: 0.7rem; color: #1e40af;">Available in your Tawk.to dashboard under Property settings.</span>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem; width: 100%;">
                                        <label style="font-size: 0.75rem; font-weight: 700; color: #1e3a8a;">Tawk.to Widget ID</label>
                                        <input 
                                            type="text" 
                                            wire:model="tawkWidgetId" 
                                            placeholder="e.g. default" 
                                            class="settings-input"
                                            style="border-color: #93c5fd; background: white; color: #1e3a8a;"
                                        />
                                        <span style="font-size: 0.7rem; color: #1e40af;">Typically 'default' or a short code next to the property.</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Responsive Save Bar (Full-width button on mobile, inline on desktop) --}}
            <div class="settings-save-bar">
                <span style="font-size: 0.75rem; color: #6b7280; text-align: center;">Changes apply immediately to your live storefront upon saving.</span>
                <button type="submit" wire:loading.attr="disabled" class="settings-save-btn">
                    <span wire:loading.remove wire:target="saveSettings">Save Settings</span>
                    <span wire:loading wire:target="saveSettings">Saving...</span>
                </button>
            </div>

        </form>
    </div>
</x-filament-panels::page>
