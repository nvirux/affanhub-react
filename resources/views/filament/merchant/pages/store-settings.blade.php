<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $hasCustomBranding = $tenant->hasFeature('custom_branding');
        $hasCustomEmail = $tenant->hasFeature('custom_email');
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

    <div 
        class="settings-container"
        x-data="{
            init() {
                const updateTabFromHash = () => {
                    const hash = window.location.hash.replace('#', '');
                    if (['profile', 'branding', 'contact', 'email'].includes(hash)) {
                        $wire.setTab(hash);
                    }
                };
                updateTabFromHash();
                window.addEventListener('hashchange', updateTabFromHash);
            },
            switchTab(tab) {
                window.location.hash = tab;
                $wire.setTab(tab);
            }
        }"
    >
        
        {{-- Responsive 4-Tab Bar (2x2 tactile grid on mobile, horizontal pill row on tablet/desktop) --}}
        <div class="settings-tabs-wrapper">
            
            {{-- Tab 1: Profile --}}
            <button 
                type="button" 
                @click="switchTab('profile')" 
                class="settings-tab-btn {{ $activeTab === 'profile' ? 'is-active' : '' }}"
            >
                <span>🏷️</span> Store Profile
            </button>

            {{-- Tab 2: Theme & Branding --}}
            <button 
                type="button" 
                @click="switchTab('branding')" 
                class="settings-tab-btn {{ $activeTab === 'branding' ? 'is-active' : '' }}"
            >
                <span>🎨</span> Theme & Branding
            </button>

            {{-- Tab 3: Contact & Support --}}
            <button 
                type="button" 
                @click="switchTab('contact')" 
                class="settings-tab-btn {{ $activeTab === 'contact' ? 'is-active' : '' }}"
            >
                <span>📞</span> Contact & Support
            </button>

            {{-- Tab 4: Custom Email --}}
            <button 
                type="button" 
                @click="switchTab('email')" 
                class="settings-tab-btn {{ $activeTab === 'email' ? 'is-active' : '' }}"
            >
                <span>✉️</span> Custom Email
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
                        <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Update your public storefront name, address, and mobile dashboard tagline.</p>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                    <div style="display: flex; flex-direction: column; gap: 1.25rem; width: 100%;">
                        {{-- Quick Link to Brand Assets --}}
                        <div style="background-color: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 14px; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 0.875rem;">
                                <div style="width: 42px; height: 42px; border-radius: 10px; background-color: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                    🎨
                                </div>
                                <div>
                                    <h4 style="font-size: 0.875rem; font-weight: 800; color: #0f172a; margin: 0;">Store Logo & Browser Favicon</h4>
                                    <p style="font-size: 0.75rem; color: #64748b; margin: 0.2rem 0 0 0;">Upload your custom brand logo and browser tab icon in Theme & Branding.</p>
                                </div>
                            </div>
                            <button type="button" @click="switchTab('branding')" style="display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.75rem; font-weight: 700; color: #2563eb; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 0.5rem 1rem; cursor: pointer; transition: all 0.15s ease;">
                                Manage Brand Assets &rarr;
                            </button>
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
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Subdomain / Web Address</label>
                            @php
                                $primaryDomain = $tenant->getPrimaryDomain() ?? ($tenant->public_id . '.' . (parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost'));
                                $port = request()->getPort();
                                $portSuffix = ($port && ! in_array((int) $port, [80, 443]) && ! str_contains($primaryDomain, ':')) ? ":{$port}" : '';
                                $displayDomain = $primaryDomain . $portSuffix;
                            @endphp
                            <input 
                                type="text" 
                                value="{{ $displayDomain }}" 
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
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Storefront Theme & Branding</h3>
                        <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Customize your brand assets, storefront design template, and color palette.</p>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                    {{-- Brand Visual Identity (Store Logo & Browser Favicon) --}}
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%;">
                        <div>
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151; display: block;">Brand Visual Identity</label>
                            <span style="font-size: 0.75rem; color: #6b7280;">Upload your official store logo and browser favicon (tab icon) displayed on customer devices.</span>
                        </div>

                        <div class="settings-grid-2">
                            {{-- Store Logo Upload --}}
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: #ffffff; border: 1px solid #e5e7eb; padding: 1rem; border-radius: 12px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Store Logo</label>
                                    <span style="font-size: 0.7rem; color: #6b7280;">PNG / JPG / WEBP (max 5MB)</span>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; background: #f9fafb; padding: 0.75rem; border-radius: 10px; border: 1px dashed #d1d5db; min-height: 64px;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        @if ($logoPath)
                                            <div style="position: relative; width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($logoPath) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 0.125rem;">
                                                <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Active Logo</span>
                                                <button type="button" wire:click="removeLogo" style="font-size: 0.75rem; font-weight: 700; color: #dc2626; text-align: left; background: none; border: none; cursor: pointer; padding: 0;">Remove Logo</button>
                                            </div>
                                        @elseif ($logo)
                                            @php
                                                $logoPreview = null;
                                                try {
                                                    $logoPreview = $logo->temporaryUrl();
                                                } catch (\Throwable) {
                                                    $logoPreview = null;
                                                }
                                            @endphp
                                            <div style="position: relative; width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                @if ($logoPreview)
                                                    <img src="{{ $logoPreview }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @else
                                                    <span style="font-size: 1.25rem;">🖼️</span>
                                                @endif
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 0.125rem;">
                                                <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">New logo selected</span>
                                                <span style="font-size: 0.75rem; color: #16a34a; font-weight: 600;">Ready to save</span>
                                            </div>
                                        @else
                                            <div style="width: 48px; height: 48px; border-radius: 8px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 1.125rem; font-weight: 800; flex-shrink: 0;">
                                                {{ substr($name, 0, 1) ?: 'S' }}
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 0.125rem;">
                                                <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Default initial</span>
                                                <span style="font-size: 0.7rem; color: #6b7280;">No custom logo</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <input type="file" id="logoInput" wire:model="logo" style="display: none;" accept="image/*">
                                        <label for="logoInput" style="background-color: white; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.4rem 0.75rem; font-size: 0.75rem; font-weight: 700; color: #374151; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); white-space: nowrap;">
                                            Upload
                                        </label>
                                    </div>
                                </div>
                                @error('logo') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                            </div>

                            {{-- Browser Favicon Upload (Available for all plans) --}}
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: #ffffff; border: 1px solid #e5e7eb; padding: 1rem; border-radius: 12px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Browser Favicon (Tab Icon)</label>
                                    <span style="font-size: 0.7rem; color: #6b7280;">.png, .ico, .svg (max 2MB)</span>
                                </div>

                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; background: #f9fafb; padding: 0.75rem; border-radius: 10px; border: 1px dashed #d1d5db; min-height: 64px;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        @if ($faviconPath)
                                            <div style="width: 40px; height: 40px; border-radius: 6px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($faviconPath) }}" style="max-width: 28px; max-height: 28px; object-fit: contain;">
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 0.125rem;">
                                                <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Custom Favicon</span>
                                                <button type="button" wire:click="removeFavicon" style="font-size: 0.75rem; font-weight: 700; color: #dc2626; text-align: left; background: none; border: none; cursor: pointer; padding: 0;">Remove</button>
                                            </div>
                                        @elseif ($favicon)
                                            @php
                                                $faviconPreview = null;
                                                try {
                                                    $faviconPreview = $favicon->temporaryUrl();
                                                } catch (\Throwable) {
                                                    $faviconPreview = null;
                                                }
                                            @endphp
                                            <div style="width: 40px; height: 40px; border-radius: 6px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                @if ($faviconPreview)
                                                    <img src="{{ $faviconPreview }}" style="max-width: 28px; max-height: 28px; object-fit: contain;">
                                                @else
                                                    <span style="font-size: 1.125rem;">🌐</span>
                                                @endif
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 0.125rem;">
                                                <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">New icon selected</span>
                                                <span style="font-size: 0.75rem; color: #16a34a; font-weight: 600;">Ready to save</span>
                                            </div>
                                        @else
                                            <div style="width: 40px; height: 40px; border-radius: 6px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 1rem; flex-shrink: 0;">
                                                🌐
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 0.125rem;">
                                                <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Default Icon</span>
                                                <span style="font-size: 0.7rem; color: #6b7280;">Ready to customize</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <input type="file" id="faviconInput" wire:model="favicon" style="display: none;" accept=".png,.ico,.svg,image/png,image/x-icon,image/svg+xml">
                                        <label for="faviconInput" style="background-color: white; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.4rem 0.75rem; font-size: 0.75rem; font-weight: 700; color: #374151; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); white-space: nowrap;">
                                            Upload
                                        </label>
                                    </div>
                                </div>
                                @error('favicon') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0.5rem 0 0 0;">

                    {{-- Landing Page Template Picker --}}
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                            <div>
                                <label style="font-size: 0.875rem; font-weight: 700; color: #374151; display: block;">Landing Page Design</label>
                                <span style="font-size: 0.75rem; color: #6b7280;">Choose the design and user flow for your customers when they visit your store.</span>
                            </div>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #0284c7; background: #f0f9ff; padding: 0.125rem 0.5rem; border-radius: 9999px; border: 1px solid #bae6fd;">Instant Switch</span>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; width: 100%; box-sizing: border-box;">
                            
                            {{-- Option 1: Default Theme (Modern Hub) --}}
                            @php 
                                $currentTpl = $landingPageTemplate ?? 'default';
                                $isDefaultTpl = in_array($currentTpl, ['default', 'yushpay'], true);
                            @endphp
                            <div 
                                wire:click="selectTemplate('default')"
                                style="position: relative; border-radius: 16px; border: 2px solid {{ $isDefaultTpl ? '#10b981' : '#e2e8f0' }}; background: {{ $isDefaultTpl ? '#f0fdf4' : '#ffffff' }}; padding: 0.875rem; cursor: pointer; transition: all 0.2s ease; display: flex; flex-direction: column; gap: 0.75rem; box-shadow: {{ $isDefaultTpl ? '0 8px 20px rgba(16, 185, 129, 0.15)' : '0 2px 6px rgba(0,0,0,0.03)' }};"
                            >
                                {{-- Theme Preview Image --}}
                                <div style="position: relative; width: 100%; height: 160px; border-radius: 10px; overflow: hidden; background: #0f172a; border: 1px solid {{ $isDefaultTpl ? '#86efac' : '#e2e8f0' }};">
                                    <img 
                                        src="/images/themes/default-hub.jpg" 
                                        alt="Default Theme Preview" 
                                        style="width: 100%; height: 100%; object-fit: cover; object-position: top center; transition: transform 0.3s ease;"
                                        onmouseover="this.style.transform='scale(1.03)'"
                                        onmouseout="this.style.transform='scale(1)'"
                                    />
                                    <span style="position: absolute; top: 8px; left: 8px; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); color: #ffffff; font-size: 0.6875rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 6px;">
                                        Default
                                    </span>
                                    @if($isDefaultTpl)
                                        <span style="position: absolute; top: 8px; right: 8px; background: #10b981; color: white; font-size: 0.6875rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 9999px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4); display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <svg style="width: 11px; height: 11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Active
                                        </span>
                                    @endif
                                </div>

                                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                                    <div>
                                        <div style="font-size: 0.9375rem; font-weight: 800; color: #0f172a;">Default Theme</div>
                                        <div style="font-size: 0.72rem; color: #64748b; font-weight: 500;">Modern Fintech & Support Hub</div>
                                    </div>
                                    @if($isDefaultTpl)
                                        <span style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.6875rem; font-weight: 700; color: #059669; background: #d1fae5; padding: 0.125rem 0.5rem; border-radius: 9999px;">
                                            Active
                                        </span>
                                    @endif
                                </div>

                                <p style="font-size: 0.75rem; color: #475569; margin: 0; line-height: 1.45;">
                                    High-impact financial hub with APK download banner, mock dashboard wallet card, 6-card feature grid, 4 direct support channels, and dynamic merchant colors.
                                </p>

                                <div style="display: flex; gap: 0.375rem; flex-wrap: wrap; margin-top: auto; padding-top: 0.5rem; border-top: 1px dashed {{ $isDefaultTpl ? '#86efac' : '#f1f5f9' }};">
                                    <span style="font-size: 0.6875rem; background: #ecfdf5; color: #065f46; padding: 0.125rem 0.4rem; border-radius: 4px; font-weight: 600;">Hub Layout</span>
                                    <span style="font-size: 0.6875rem; background: #f1f5f9; color: #334155; padding: 0.125rem 0.4rem; border-radius: 4px; font-weight: 500;">Support Grid</span>
                                    <span style="font-size: 0.6875rem; background: #f1f5f9; color: #334155; padding: 0.125rem 0.4rem; border-radius: 4px; font-weight: 500;">APK Banner</span>
                                </div>
                            </div>

                            {{-- Option 2: Classic Fintech (v1) --}}
                            @php 
                                $isClassicTpl = in_array($currentTpl, ['classic', 'legacy', 'v1'], true);
                            @endphp
                            <div 
                                wire:click="selectTemplate('classic')"
                                style="position: relative; border-radius: 16px; border: 2px solid {{ $isClassicTpl ? '#10b981' : '#e2e8f0' }}; background: {{ $isClassicTpl ? '#f0fdf4' : '#ffffff' }}; padding: 0.875rem; cursor: pointer; transition: all 0.2s ease; display: flex; flex-direction: column; gap: 0.75rem; box-shadow: {{ $isClassicTpl ? '0 8px 20px rgba(16, 185, 129, 0.15)' : '0 2px 6px rgba(0,0,0,0.03)' }};"
                            >
                                {{-- Theme Preview Image --}}
                                <div style="position: relative; width: 100%; height: 160px; border-radius: 10px; overflow: hidden; background: #0f172a; border: 1px solid {{ $isClassicTpl ? '#86efac' : '#e2e8f0' }};">
                                    <img 
                                        src="/images/themes/classic-v1.jpg" 
                                        alt="Classic Fintech v1 Preview" 
                                        style="width: 100%; height: 100%; object-fit: cover; object-position: top center; transition: transform 0.3s ease;"
                                        onmouseover="this.style.transform='scale(1.03)'"
                                        onmouseout="this.style.transform='scale(1)'"
                                    />
                                    <span style="position: absolute; top: 8px; left: 8px; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); color: #ffffff; font-size: 0.6875rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 6px;">
                                        Classic
                                    </span>
                                    @if($isClassicTpl)
                                        <span style="position: absolute; top: 8px; right: 8px; background: #10b981; color: white; font-size: 0.6875rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 9999px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4); display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <svg style="width: 11px; height: 11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Active
                                        </span>
                                    @endif
                                </div>

                                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                                    <div>
                                        <div style="font-size: 0.9375rem; font-weight: 800; color: #0f172a;">Classic Fintech (v1)</div>
                                        <div style="font-size: 0.72rem; color: #64748b; font-weight: 500;">Comprehensive & Brand-Focused</div>
                                    </div>
                                    @if($isClassicTpl)
                                        <span style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.6875rem; font-weight: 700; color: #059669; background: #d1fae5; padding: 0.125rem 0.5rem; border-radius: 9999px;">
                                            Active
                                        </span>
                                    @endif
                                </div>

                                <p style="font-size: 0.75rem; color: #475569; margin: 0; line-height: 1.45;">
                                    Classic multi-section fintech layout featuring hero trust stats, live service catalogue, interactive steps, FAQs, and extensive brand highlights.
                                </p>

                                <div style="display: flex; gap: 0.375rem; flex-wrap: wrap; margin-top: auto; padding-top: 0.5rem; border-top: 1px dashed {{ $isClassicTpl ? '#86efac' : '#f1f5f9' }};">
                                    <span style="font-size: 0.6875rem; background: #f1f5f9; color: #334155; padding: 0.125rem 0.4rem; border-radius: 4px; font-weight: 500;">Multi-Section</span>
                                    <span style="font-size: 0.6875rem; background: #f1f5f9; color: #334155; padding: 0.125rem 0.4rem; border-radius: 4px; font-weight: 500;">Rich FAQs</span>
                                    <span style="font-size: 0.6875rem; background: #f1f5f9; color: #334155; padding: 0.125rem 0.4rem; border-radius: 4px; font-weight: 500;">Catalog Grid</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0.5rem 0 0 0;">

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


                </div>
            @endif

            {{-- ────────────────────────────────────────────────────────
                TAB 3: CONTACT & SUPPORT
                ──────────────────────────────────────────────────────── --}}
            @if ($activeTab === 'contact')
                <div class="settings-card">
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Contact & Support Channels</h3>
                        <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Direct customer support channels, WhatsApp chat, and community links shown on your storefront.</p>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                    {{-- Phone Support (Max 2 Numbers) --}}
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%;">
                        <div>
                            <h4 style="font-size: 0.9375rem; font-weight: 800; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                <span>📞</span> Phone Support Lines (Max 2 Numbers)
                            </h4>
                            <p style="font-size: 0.75rem; color: #64748b; margin: 0.25rem 0 0 0;">These numbers will appear as direct tap-to-call buttons on your storefront Phone Support card.</p>
                        </div>

                        <div class="settings-grid-2">
                            <div style="display: flex; flex-direction: column; gap: 0.375rem; width: 100%;">
                                <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Phone Support Line 1 (Primary)</label>
                                <input 
                                    type="text" 
                                    wire:model="contactPhone" 
                                    placeholder="e.g. 08101360986 or +234 810 136 0986" 
                                    class="settings-input"
                                />
                                @error('contactPhone') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.375rem; width: 100%;">
                                <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Phone Support Line 2 (Secondary, Optional)</label>
                                <input 
                                    type="text" 
                                    wire:model="contactPhone2" 
                                    placeholder="e.g. 09061730442 or +234 906 173 0442" 
                                    class="settings-input"
                                />
                                @error('contactPhone2') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0.25rem 0 0 0;">

                    {{-- WhatsApp Customer Support & Floating Bubble --}}
                    <div style="display: flex; flex-direction: column; gap: 0.875rem; background: #f0fdf4; border: 1.5px solid #bbf7d0; padding: 1.25rem; border-radius: 14px; width: 100%; box-sizing: border-box;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;">
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #14532d; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 18px; height: 18px; fill: #15803d; flex-shrink: 0;" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.963C16.588 2.02 14.12 1.002 11.5 1.002c-5.442 0-9.87 4.372-9.874 9.802-.001 1.769.471 3.498 1.362 5.031L2.002 21.5l5.727-1.492zm11.233-5.918c-.3-.15-1.771-.875-2.046-.975-.276-.1-.477-.15-.677.15-.2.3-.777.975-.951 1.175-.175.2-.35.225-.65.075-3.04-1.522-4.14-2.522-4.9-3.825-.2-.35-.022-.538.127-.687.135-.135.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.677-1.625-.926-2.225-.244-.589-.493-.51-.677-.52l-.578-.01c-.2 0-.525.075-.8.375-.275.3-1.05 1.025-1.05 2.5s1.07 2.9 1.22 3.1c.15.2 2.105 3.213 5.098 4.5 1.205.52 2.146.83 2.875 1.06.73.23 1.396.197 1.92.12.584-.087 1.771-.725 2.021-1.425.25-.7.25-1.3 1.75-1.425-.075-.125-.375-.275-.675-.425z"/></svg>
                                    WhatsApp Support & Floating Chat
                                </h4>
                                <p style="font-size: 0.8125rem; color: #166534; margin: 0.25rem 0 0 0;">Direct WhatsApp chat link on your storefront cards and floating chat bubble.</p>
                            </div>
                            <div>
                                @if(! empty(trim($whatsappChatPhone ?? '')))
                                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; font-weight: 700; color: #15803d; background: #dcfce7; border: 1px solid #86efac; padding: 0.25rem 0.65rem; border-radius: 9999px;">
                                        <span style="width: 7px; height: 7px; border-radius: 9999px; background: #16a34a;"></span>
                                        Active & Floating Chat Enabled
                                    </span>
                                @else
                                    <span style="font-size: 0.72rem; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.25rem 0.6rem; border-radius: 9999px;">
                                        Add number to activate
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="settings-grid-2" style="margin-top: 0.25rem; border-top: 1px dashed #bbf7d0; padding-top: 0.875rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.25rem; width: 100%;">
                                <label style="font-size: 0.75rem; font-weight: 700; color: #14532d;">WhatsApp Direct Support Number</label>
                                <input 
                                    type="text" 
                                    wire:model.live="whatsappChatPhone" 
                                    placeholder="e.g. 09025293759, 080..., or 2349025293759" 
                                    class="settings-input"
                                    style="border-color: #86efac; background: white; color: #14532d;"
                                />
                                <span style="font-size: 0.7rem; color: #166534;">You can type 090..., 080..., or 234... Adding a number automatically enables your WhatsApp support card and floating chat button.</span>
                                @error('whatsappChatPhone') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
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
                                <span style="font-size: 0.7rem; color: #166534;">Opens automatically when customers tap to chat on WhatsApp.</span>
                                @error('whatsappChatMessage') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0.25rem 0 0 0;">

                    {{-- Community & Social Links --}}
                    <div class="settings-grid-2">
                        {{-- WhatsApp Community Link --}}
                        <div class="settings-col-span-2" style="display: flex; flex-direction: column; gap: 0.375rem; width: 100%;">
                            <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">WhatsApp Channel / Group Link</label>
                            <input 
                                type="text" 
                                wire:model="socialWhatsapp" 
                                placeholder="e.g. https://chat.whatsapp.com/..." 
                                class="settings-input"
                            />
                            <span style="font-size: 0.7rem; color: #6b7280;">Official community or broadcast link shown on your WhatsApp Group support card.</span>
                            @error('socialWhatsapp') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Contact Email --}}
                        <div style="display: flex; flex-direction: column; gap: 0.375rem; width: 100%;">
                            <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Support Email Address</label>
                            <input 
                                type="email" 
                                wire:model="contactEmail" 
                                placeholder="e.g. support@mybrand.com" 
                                class="settings-input"
                            />
                            @error('contactEmail') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>

                        {{-- Instagram --}}
                        <div style="display: flex; flex-direction: column; gap: 0.375rem; width: 100%;">
                            <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Instagram Handle</label>
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
                        <div class="settings-col-span-2" style="display: flex; flex-direction: column; gap: 0.375rem; width: 100%;">
                            <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Facebook Page URL</label>
                            <input 
                                type="text" 
                                wire:model="socialFacebook" 
                                placeholder="https://facebook.com/mybrand" 
                                class="settings-input"
                            />
                            @error('socialFacebook') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- ────────────────────────────────────────────────────────
                TAB 4: CUSTOM EMAIL (RESEND)
                ──────────────────────────────────────────────────────── --}}
            @if ($activeTab === 'email')
                <div class="settings-card">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <div>
                            <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Custom Email Delivery (Resend)</h3>
                            <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">
                                Send customer password resets, login alerts, and receipts directly from your own domain email address via Resend.
                            </p>
                        </div>
                        @if ($hasCustomEmail && ! empty($resendApiKey) && ! empty($resendFromEmail))
                            <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #10b981;"></span>
                                Active & Connected
                            </span>
                        @endif
                    </div>

                    @if (! $hasCustomEmail)
                        {{-- Upgrade Prompt for Starter Plan --}}
                        <div style="background-color: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; align-items: flex-start; gap: 0.875rem;">
                                <div style="width: 2.5rem; height: 2.5rem; border-radius: 10px; background-color: #dbeafe; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                    🔒
                                </div>
                                <div>
                                    <h4 style="font-size: 0.9375rem; font-weight: 800; color: #1e3a8a; margin: 0;">Custom Email is a Pro Feature</h4>
                                    <p style="font-size: 0.8125rem; color: #1e40af; margin-top: 0.25rem; margin-bottom: 0; line-height: 1.4;">
                                        Your store currently sends customer transactional emails (like password resets) through AffanHub's shared delivery network, branded with your store name. 
                                        Upgrade to <strong>Pro</strong> or <strong>Enterprise</strong> to connect your own Resend account and send emails directly from your own verified domain (e.g. <code>support@yourdomain.com</code>).
                                    </p>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: flex-end; border-top: 1px solid #bfdbfe; padding-top: 0.75rem;">
                                <a href="{{ \App\Filament\Merchant\Pages\Billing::getUrl() }}" style="display: inline-flex; align-items: center; gap: 0.375rem; background-color: #2563eb; color: white; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 700; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                    Upgrade to Pro Plan &rarr;
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- Active Configuration for Pro / Enterprise --}}
                        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; font-size: 0.8125rem; color: #475569; line-height: 1.5;">
                            <strong>How to set up Resend:</strong>
                            <ol style="margin: 0.5rem 0 0; padding-left: 1.25rem; display: flex; flex-direction: column; gap: 0.25rem;">
                                <li>Sign up or log in at <a href="https://resend.com" target="_blank" style="color: #2563eb; font-weight: 600; text-decoration: underline;">resend.com</a>.</li>
                                <li>Add and verify your custom domain in your Resend Domains dashboard.</li>
                                <li>Create an API key with <em>"Sending access"</em> and paste it below.</li>
                            </ol>
                        </div>

                        <div class="settings-grid-2">
                            <div style="display: flex; flex-direction: column; gap: 0.375rem; width: 100%;">
                                <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Resend API Key <span style="color: #ef4444;">*</span></label>
                                <input 
                                    type="password" 
                                    wire:model="resendApiKey" 
                                    placeholder="re_123456789_abcdefg..." 
                                    class="settings-input"
                                    autocomplete="off"
                                />
                                @error('resendApiKey') <span style="font-size: 0.75rem; color: #ef4444; font-weight: 600;">{{ $message }}</span> @enderror
                                <span style="font-size: 0.75rem; color: #6b7280;">Stored encrypted at rest in your store database.</span>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.375rem; width: 100%;">
                                <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Sender Email Address ("From") <span style="color: #ef4444;">*</span></label>
                                <input 
                                    type="email" 
                                    wire:model="resendFromEmail" 
                                    placeholder="e.g. support@yourdomain.com" 
                                    class="settings-input"
                                />
                                @error('resendFromEmail') <span style="font-size: 0.75rem; color: #ef4444; font-weight: 600;">{{ $message }}</span> @enderror
                                <span style="font-size: 0.75rem; color: #6b7280;">Must match a domain that is verified in your Resend dashboard.</span>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.375rem; width: 100%;">
                                <label style="font-size: 0.8125rem; font-weight: 700; color: #374151;">Sender Display Name</label>
                                <input 
                                    type="text" 
                                    wire:model="resendFromName" 
                                    placeholder="{{ $name }}" 
                                    class="settings-input"
                                />
                                <span style="font-size: 0.75rem; color: #6b7280;">Defaults to your Store Name (<strong>{{ $name }}</strong>) if left blank.</span>
                            </div>
                        </div>

                        {{-- Test Connection Card --}}
                        <div style="background-color: white; border: 1.5px dashed #cbd5e1; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.875rem;">
                            <div>
                                <h4 style="font-size: 0.875rem; font-weight: 800; color: #0f172a; margin: 0;">Test Email Connection</h4>
                                <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; margin-bottom: 0;">
                                    Send a real-time verification email to ensure your Resend API key and sender address are operating before saving.
                                </p>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                <input 
                                    type="email" 
                                    wire:model="testEmailRecipient" 
                                    placeholder="your-email@example.com" 
                                    class="settings-input"
                                    style="max-width: 320px;"
                                />
                                <button 
                                    type="button" 
                                    wire:click="sendTestEmail" 
                                    wire:loading.attr="disabled"
                                    style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.625rem 1.25rem; border-radius: 10px; font-size: 0.8125rem; font-weight: 700; background-color: #0f172a; color: white; border: none; cursor: pointer; transition: all 0.15s ease;"
                                >
                                    <span wire:loading.remove wire:target="sendTestEmail">📨 Send Test Email</span>
                                    <span wire:loading wire:target="sendTestEmail">Sending Test...</span>
                                </button>
                            </div>
                            @error('testEmailRecipient') <span style="font-size: 0.75rem; color: #ef4444; font-weight: 600;">{{ $message }}</span> @enderror
                        </div>
                    @endif
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
