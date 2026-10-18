<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $wallet = $tenant->mainWallet();
        $walletBalance = (float) ($wallet->balance ?? 0.00);
        $app = $mobileApp;
        $isReady = $app && $app->isReady();
        $isBuilding = $app && $app->isBuilding();
        $hasPaidSetup = $this->has_paid_setup;
        $hasPaidPlaystore = $this->has_paid_playstore;
        $downloadUrl = route('merchant.mobile-app.download', ['store' => $tenant->public_id]);
    @endphp

    <style>
        .app-manager-container {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            box-sizing: border-box;
        }

        .mam-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
            box-sizing: border-box;
        }

        .mam-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        @media (min-width: 1024px) {
            .mam-grid {
                grid-template-columns: 1.25fr 1fr;
            }
        }

        .mam-toggle-card {
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            border-radius: 14px;
            padding: 1.15rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mam-toggle-card:hover {
            border-color: #cbd5e1;
        }

        .mam-toggle-card.is-selected {
            border-color: #10b981;
            background: #f0fdf4;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.1);
        }

        .mam-btn-primary {
            width: 100%;
            background: #0f172a;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.95rem;
            padding: 0.9rem 1.25rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.15s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .mam-btn-primary:hover {
            background: #1e293b;
        }

        .mam-btn-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        }

        .mam-btn-green:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .mam-btn-amber {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
        }

        .mam-btn-amber:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        }

        .payment-tab-btn {
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.15s ease;
        }

        .payment-tab-btn.is-active {
            border-color: #f59e0b;
            background: #fffdf7;
            box-shadow: 0 2px 6px rgba(245, 158, 11, 0.12);
        }
    </style>

    {{-- Automatic Polling: when building, silently check GitHub every 7 seconds --}}
    <div class="app-manager-container" @if($isBuilding) wire:poll.7s="checkBuildStatus" @endif>

        {{-- Top App Header & Quick Action Card --}}
        <div class="mam-card">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                
                <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
                    {{-- Left: Store Logo & Details --}}
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 54px; height: 54px; border-radius: 14px; background: #f8fafc; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                            @if($iconChoice === 'custom' && $appIcon)
                                <img src="{{ $appIcon->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover;" />
                            @elseif($app && $app->app_icon_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($app->app_icon_path) }}" style="width: 100%; height: 100%; object-fit: cover;" />
                            @elseif($tenant->logo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($tenant->logo_path) }}" style="width: 100%; height: 100%; object-fit: contain;" />
                            @else
                                <span style="font-size: 1.6rem;">📱</span>
                            @endif
                        </div>

                        <div>
                            <div style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap;">
                                <h2 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.01em;">
                                    {{ $appName ?: $tenant->name }} Mobile App
                                </h2>

                                @if($isReady)
                                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.2rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;">
                                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981;"></span>
                                        v{{ $app->version_name }} Ready
                                    </span>
                                @elseif($isBuilding)
                                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.2rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background-color: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
                                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #f59e0b; animation: pulse 1.5s infinite;"></span>
                                        Cloud Compiling ⏳
                                    </span>
                                @else
                                    <span style="padding: 0.2rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background-color: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;">
                                        Ready to Build
                                    </span>
                                @endif
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.65rem; margin-top: 0.35rem; flex-wrap: wrap;">
                                <span style="font-size: 0.8125rem; color: #64748b;">
                                    Package: <code style="font-family: monospace; font-weight: 700; color: #0284c7; background: #f1f5f9; padding: 0.15rem 0.45rem; border-radius: 6px; border: 1px solid #e2e8f0;">{{ $this->package_id }}</code>
                                </span>
                                @if($app && $app->last_built_at)
                                    <span style="font-size: 0.75rem; color: #94a3b8;">
                                        • Built {{ $app->last_built_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right: Actions --}}
                    <div style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap;">
                        <button wire:click="checkBuildStatus" 
                                wire:loading.attr="disabled"
                                type="button"
                                style="padding: 0.6rem 1rem; background: #ffffff; border: 1.5px solid #d1d5db; color: #374151; font-size: 0.8125rem; font-weight: 700; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                            <span wire:loading.remove wire:target="checkBuildStatus">🔄 Refresh Status</span>
                            <span wire:loading wire:target="checkBuildStatus">Checking...</span>
                        </button>

                        @if($isReady)
                            <a href="{{ $downloadUrl }}" 
                               download
                               class="mam-btn-primary mam-btn-green"
                               style="width: auto; padding: 0.6rem 1.25rem; text-decoration: none; font-size: 0.8125rem;">
                                <span>📥 Download APK (v{{ $app->version_name }})</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Cloud Compilation Alert when building --}}
                @if($isBuilding)
                    <div style="padding: 0.95rem 1.15rem; background: #fffdf5; border: 1px solid #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 20px; height: 20px; border: 2.5px solid #d97706; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; flex-shrink: 0;"></div>
                            <div>
                                <span style="font-size: 0.875rem; font-weight: 800; color: #92400e; display: block;">
                                    Assembling v{{ $app->version_name }} Android APK in the Cloud...
                                </span>
                                <span style="font-size: 0.75rem; color: #b45309;">
                                    Cloud build runners are compiling your app. This typically takes 60–90 seconds. This page auto-refreshes when ready.
                                </span>
                            </div>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #92400e; background: #fef3c7; padding: 0.2rem 0.5rem; border-radius: 6px;">
                            Auto-checking
                        </span>
                    </div>
                @endif

            </div>
        </div>

        {{-- Platform Eligibility Banners (if any restrictions active) --}}
        @if(! $this->isBuilderEnabled())
            <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 14px; padding: 1.15rem 1.35rem; display: flex; align-items: flex-start; gap: 0.85rem;">
                <div style="font-size: 1.5rem;">⏸️</div>
                <div>
                    <h4 style="font-size: 0.9rem; font-weight: 800; color: #991b1b; margin: 0;">App Compilation Paused</h4>
                    <p style="font-size: 0.8rem; color: #b91c1c; margin-top: 0.25rem; margin-bottom: 0;">
                        Platform administrators have temporarily paused new mobile app compilations. Previously compiled apps remain fully downloadable.
                    </p>
                </div>
            </div>
        @elseif($this->requireCustomDomain() && ! $this->hasVerifiedCustomDomain())
            <div style="background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 14px; padding: 1.15rem 1.35rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="font-size: 1.5rem;">🌐</div>
                    <div>
                        <h4 style="font-size: 0.9rem; font-weight: 800; color: #1e40af; margin: 0;">Verified Custom Domain Required</h4>
                        <p style="font-size: 0.8rem; color: #1d4ed8; margin-top: 0.25rem; margin-bottom: 0;">
                            Connect and verify your custom domain (e.g. <code>yourbrand.com</code>) to compile a branded mobile app.
                        </p>
                    </div>
                </div>
                <a href="{{ route('filament.merchant.resources.domains.index', ['tenant' => $tenant->public_id]) }}" 
                   style="background: #2563eb; color: white; padding: 0.55rem 1rem; border-radius: 8px; font-size: 0.8rem; font-weight: 800; text-decoration: none;">
                    Connect Custom Domain &rarr;
                </a>
            </div>
        @elseif(! $this->isPlanAllowed())
            <div style="background: #fdf4ff; border: 1.5px solid #f0abfc; border-radius: 14px; padding: 1.15rem 1.35rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <div style="font-size: 1.5rem;">⭐</div>
                    <div>
                        <h4 style="font-size: 0.9rem; font-weight: 800; color: #86198f; margin: 0;">Plan Upgrade Required</h4>
                        <p style="font-size: 0.8rem; color: #a21caf; margin-top: 0.25rem; margin-bottom: 0;">
                            {{ $this->getEligibilityBlockReason() }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('filament.merchant.pages.billing', ['tenant' => $tenant->public_id]) }}" 
                   style="background: #9333ea; color: white; padding: 0.55rem 1rem; border-radius: 8px; font-size: 0.8rem; font-weight: 800; text-decoration: none;">
                    Upgrade Plan &rarr;
                </a>
            </div>
        @endif

        {{-- When App is Ready: Share & Install Card --}}
        @if($isReady)
            <div class="mam-card" style="border: 1.5px solid #a7f3d0; background: #f0fdf4;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <h3 style="font-size: 1.1rem; font-weight: 800; color: #065f46; margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                            <span>🎉</span>
                            <span>Your Android App is Ready!</span>
                        </h3>
                        <p style="font-size: 0.8125rem; color: #047857; margin-top: 0.2rem; margin-bottom: 0;">
                            Share this download link or QR code with your customers so they can install your app on their Android phones.
                        </p>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; background: #d1fae5; color: #065f46; padding: 0.25rem 0.65rem; border-radius: 9999px;">
                        Version {{ $app->version_name }}
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;" class="ready-grid">
                    <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                        {{-- Copy Direct Download Link --}}
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #065f46; margin-bottom: 0.35rem;">
                                Customer APK Download URL
                            </label>
                            <div style="display: flex; align-items: center; gap: 0.5rem;" x-data="{ copied: false }">
                                <input type="text" 
                                       readonly 
                                       value="{{ $downloadUrl }}" 
                                       style="width: 100%; border: 1.5px solid #a7f3d0; background: #ffffff; border-radius: 10px; padding: 0.65rem 0.85rem; font-size: 0.85rem; font-family: monospace; color: #0f172a;" />
                                <button type="button"
                                        x-on:click="navigator.clipboard.writeText('{{ $downloadUrl }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                        style="padding: 0.65rem 1rem; background: #065f46; color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 0.8rem; cursor: pointer; white-space: nowrap; flex-shrink: 0;">
                                    <span x-show="!copied">📋 Copy Link</span>
                                    <span x-show="copied" style="display: none;">✓ Copied!</span>
                                </button>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-top: 0.25rem;">
                            <a href="{{ $downloadUrl }}" 
                               download
                               class="mam-btn-primary mam-btn-green"
                               style="width: auto; text-decoration: none;">
                                📥 Download APK (v{{ $app->version_name }})
                            </a>

                            @if($this->canCreateOrRebuildApp() && ! $isBuilding)
                                <button wire:click="requestUpdate" 
                                        wire:loading.attr="disabled"
                                        type="button"
                                        style="padding: 0.7rem 1.15rem; background: #ffffff; border: 1.5px solid #cbd5e1; color: #0f172a; font-size: 0.85rem; font-weight: 700; border-radius: 10px; cursor: pointer;">
                                    <span wire:loading.remove wire:target="requestUpdate">
                                        🔄 Rebuild Update (v{{ $app->version_code + 1 }}.0 - Free)
                                    </span>
                                    <span wire:loading wire:target="requestUpdate">
                                        Dispatching Update...
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main 2-Column Responsive Section --}}
        <div class="mam-grid">
            
            {{-- Column 1: App Identity & Customization --}}
            <div class="mam-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <div>
                        <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">
                            1. App Branding &amp; Details
                        </h3>
                        <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.2rem; margin-bottom: 0;">
                            Configure your app title, branding, and launcher icon.
                        </p>
                    </div>
                    @if($isBuilding)
                        <span style="background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 6px;">
                            🔒 Locked During Build
                        </span>
                    @endif
                </div>

                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    
                    {{-- App Display Name Input --}}
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.4rem;">
                            App Display Name <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" 
                               wire:model.live.debounce.300ms="appName" 
                               {{ $isBuilding ? 'disabled' : '' }}
                               placeholder="e.g. {{ $tenant->name }}" 
                               style="width: 100%; border: 1.5px solid #d1d5db; border-radius: 10px; padding: 0.75rem 0.95rem; font-size: 0.9rem; color: #111827; outline: none; background-color: {{ $isBuilding ? '#f8fafc' : 'white' }};" />
                        <span style="font-size: 0.75rem; color: #6b7280; display: block; margin-top: 0.35rem;">
                            The name that appears on customer phone screens below your app icon.
                        </span>
                        @error('appName') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Launcher Icon Options --}}
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.4rem;">
                            App Launcher Icon
                        </label>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; margin-bottom: 0.75rem;">
                            <div wire:click="setIconChoice('store_logo')" 
                                 class="mam-toggle-card {{ $iconChoice === 'store_logo' ? 'is-selected' : '' }}"
                                 style="padding: 0.75rem 0.85rem; display: flex; align-items: center; gap: 0.65rem;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; background: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    @if($tenant->logo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($tenant->logo_path) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
                                    @else
                                        <span>🏪</span>
                                    @endif
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <span style="font-size: 0.8125rem; font-weight: 800; color: #111827; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        Use Store Logo
                                    </span>
                                </div>
                            </div>

                            <div wire:click="setIconChoice('custom')" 
                                 class="mam-toggle-card {{ $iconChoice === 'custom' ? 'is-selected' : '' }}"
                                 style="padding: 0.75rem 0.85rem; display: flex; align-items: center; gap: 0.65rem;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; border: 1px dashed #cbd5e1; background: #f8fafc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1rem;">
                                    📁
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <span style="font-size: 0.8125rem; font-weight: 800; color: #111827; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        Upload Custom
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($iconChoice === 'custom')
                            <div style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                                <div style="width: 50px; height: 50px; border-radius: 12px; background-color: #ffffff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                    @if($appIcon)
                                        <img src="{{ $appIcon->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                    @elseif($app && $app->app_icon_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($app->app_icon_path) }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                    @else
                                        <span style="font-size: 1.4rem; color: #94a3b8;">🖼️</span>
                                    @endif
                                </div>

                                <div style="flex-grow: 1;">
                                    <input type="file" 
                                           wire:model="appIcon" 
                                           {{ $isBuilding ? 'disabled' : '' }}
                                           accept="image/png,image/jpeg,image/webp" 
                                           style="font-size: 0.8rem; color: #475569;" />
                                    <span style="font-size: 0.7rem; color: #6b7280; display: block; margin-top: 0.25rem;">
                                        Square image (PNG, JPG). Auto-resampled to 512×512 HD icon.
                                    </span>
                                </div>
                            </div>
                            @error('appIcon') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        @else
                            <div style="background-color: #f0fdf4; border: 1px solid #dcfce7; border-radius: 10px; padding: 0.65rem 0.85rem; display: flex; align-items: center; gap: 0.6rem;">
                                <span style="font-size: 1.1rem;">✨</span>
                                <span style="font-size: 0.78rem; color: #166534; line-height: 1.4;">
                                    Using your store logo. We auto-center and optimize it on a clean 512×512 square canvas.
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Target Dashboard URL Notice --}}
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.75rem 0.95rem;">
                        <span style="font-size: 0.75rem; color: #64748b; display: block; margin-bottom: 0.2rem;">
                            Native Storefront Target:
                        </span>
                        <code style="font-family: monospace; font-size: 0.8rem; font-weight: 700; color: #0284c7; word-break: break-all;">
                            {{ $this->getResolvedStoreUrl() }}
                        </code>
                    </div>

                </div>
            </div>

            {{-- Column 2: Publishing Options & Action Area --}}
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                {{-- Publishing Scope Selector --}}
                <div class="mam-card">
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">
                        2. Distribution Channels
                    </h3>
                    <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0; margin-bottom: 1rem;">
                        Choose how your customers can access and install your app.
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        
                        {{-- Channel A: Direct Android APK (Free / Included) --}}
                        <div style="border: 1.5px solid #10b981; background: #f0fdf4; border-radius: 14px; padding: 1rem 1.15rem;">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;">
                                <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                                    <div style="font-size: 1.4rem;">📦</div>
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                                            <span style="font-size: 0.9rem; font-weight: 800; color: #065f46;">
                                                Branded Android APK (.apk)
                                            </span>
                                            <span style="background: #d1fae5; color: #065f46; font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                                                {{ $setupPrice <= 0 ? 'FREE & INCLUDED' : '₦' . number_format($setupPrice, 0) }}
                                            </span>
                                        </div>
                                        <p style="font-size: 0.75rem; color: #047857; margin-top: 0.25rem; margin-bottom: 0; line-height: 1.4;">
                                            Fast, certified installable Android APK file. Share with customers via direct link or QR code.
                                        </p>
                                    </div>
                                </div>
                                <span style="color: #10b981; font-weight: 900; font-size: 1.1rem;">✓</span>
                            </div>
                        </div>

                        {{-- Channel B: Google Play Store Publishing (Optional Paid Add-on) --}}
                        <div wire:click="togglePlaystore" 
                             class="mam-toggle-card {{ $includePlaystore ? 'is-selected' : '' }}"
                             style="cursor: {{ ($app && $app->isPlayStoreRequested()) ? 'default' : 'pointer' }};">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;">
                                <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                                    <div style="font-size: 1.4rem;">🚀</div>
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap;">
                                            <span style="font-size: 0.9rem; font-weight: 800; color: #0f172a;">
                                                Google Play Store Publishing
                                            </span>
                                            <span style="background: #e0e7ff; color: #3730a3; font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                                                +₦{{ number_format($playstorePrice, 0) }}
                                            </span>
                                            @if($app && $app->isPlayStoreRequested())
                                                <span style="background: #ecfdf5; color: #059669; font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                                                    ✓ {{ ucwords(str_replace('_', ' ', $app->playstore_status)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; margin-bottom: 0; line-height: 1.4;">
                                            Our deployment team generates, cryptographically signs, and submits your app bundle (.aab) to Google Play.
                                        </p>
                                    </div>
                                </div>
                                <div style="width: 18px; height: 18px; border-radius: 5px; border: 2px solid {{ $includePlaystore ? '#10b981' : '#cbd5e1' }}; background: {{ $includePlaystore ? '#10b981' : 'transparent' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 0.15rem;">
                                    @if($includePlaystore)
                                        <span style="color: white; font-weight: 900; font-size: 0.7rem;">✓</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Action / Compilation Area --}}
                <div class="mam-card">
                    
                    {{-- CASE 1: FREE GENERATION (Total is ₦0) --}}
                    @if($this->totalPrice <= 0)
                        
                        <div style="margin-bottom: 1.15rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.9rem; font-weight: 700; color: #1e293b;">Total Amount</span>
                                <span style="font-size: 1.35rem; font-weight: 900; color: #10b981;">FREE (₦0)</span>
                            </div>
                            <span style="font-size: 0.75rem; color: #64748b; display: block; margin-top: 0.25rem;">
                                No card or store wallet balance required.
                            </span>
                        </div>

                        @if(! $this->canCreateOrRebuildApp())
                            <button disabled type="button" class="mam-btn-primary" style="background: #94a3b8; cursor: not-allowed;">
                                🔒 Compilation Unavailable
                            </button>
                            <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 0.35rem;">
                                {{ $this->getEligibilityBlockReason() }}
                            </span>
                        @elseif($isBuilding)
                            <button disabled type="button" class="mam-btn-primary" style="background: #d97706; cursor: wait;">
                                ⏳ Compiling in Cloud...
                            </button>
                        @else
                            <button wire:click="generateFreeApp" 
                                    wire:loading.attr="disabled"
                                    type="button" 
                                    class="mam-btn-primary mam-btn-green">
                                <span wire:loading.remove wire:target="generateFreeApp">
                                    🚀 {{ $app ? 'Recompile Android App (Free)' : 'Generate Android App (Free)' }}
                                </span>
                                <span wire:loading wire:target="generateFreeApp">
                                    Initiating Cloud Compilation...
                                </span>
                            </button>
                        @endif

                    {{-- CASE 2: PAYMENT REQUIRED (Google Play Publishing selected or Setup fee > 0) --}}
                    @else
                        
                        {{-- Price Summary --}}
                        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; margin-bottom: 1.15rem;">
                            @if(! $hasPaidSetup && $setupPrice > 0)
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #64748b; margin-bottom: 0.35rem;">
                                    <span>Base Android APK Build</span>
                                    <span style="font-weight: 700; color: #111827;">₦{{ number_format($setupPrice, 2) }}</span>
                                </div>
                            @endif
                            @if($includePlaystore && ! $hasPaidPlaystore)
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #64748b; margin-bottom: 0.35rem;">
                                    <span>Google Play Store Publishing</span>
                                    <span style="font-weight: 700; color: #15803d;">+₦{{ number_format($playstorePrice, 2) }}</span>
                                </div>
                            @endif
                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #cbd5e1; padding-top: 0.5rem; margin-top: 0.35rem;">
                                <span style="font-size: 0.9rem; font-weight: 700; color: #1e293b;">Total Investment</span>
                                <span style="font-size: 1.3rem; font-weight: 900; color: #d97706;">₦{{ number_format($this->totalPrice, 2) }}</span>
                            </div>
                        </div>

                        {{-- Payment Method Selection Tabs --}}
                        <div style="display: flex; flex-direction: column; gap: 0.65rem; margin-bottom: 1.15rem;">
                            
                            {{-- Wallet Option --}}
                            <div wire:click="setPaymentMethod('wallet')" 
                                 class="payment-tab-btn {{ $paymentMethod === 'wallet' ? 'is-active' : '' }}">
                                <div style="font-size: 1.3rem;">💼</div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 0.85rem; font-weight: 800; color: #111827;">Pay from Store Wallet</span>
                                        <span style="font-size: 0.75rem; font-weight: 800; color: {{ $walletBalance >= $this->totalPrice ? '#059669' : '#dc2626' }};">
                                            ₦{{ number_format($walletBalance, 2) }}
                                        </span>
                                    </div>
                                    <span style="font-size: 0.7rem; color: #64748b;">Deducted from store balance</span>
                                </div>
                                <div style="width: 16px; height: 16px; border-radius: 50%; border: 2px solid {{ $paymentMethod === 'wallet' ? '#f59e0b' : '#cbd5e1' }}; display: flex; align-items: center; justify-content: center;">
                                    @if($paymentMethod === 'wallet')
                                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b;"></div>
                                    @endif
                                </div>
                            </div>

                            {{-- PayMint Option --}}
                            <div wire:click="setPaymentMethod('paymint')" 
                                 class="payment-tab-btn {{ $paymentMethod === 'paymint' ? 'is-active' : '' }}">
                                <div style="font-size: 1.3rem;">⚡</div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 0.85rem; font-weight: 800; color: #111827;">Pay with PayMint</span>
                                        <span style="font-size: 0.7rem; font-weight: 700; background: #ecfdf5; color: #059669; padding: 0.1rem 0.45rem; border-radius: 9999px;">
                                            Instant
                                        </span>
                                    </div>
                                    <span style="font-size: 0.7rem; color: #64748b;">Card, Bank Transfer, or USSD</span>
                                </div>
                                <div style="width: 16px; height: 16px; border-radius: 50%; border: 2px solid {{ $paymentMethod === 'paymint' ? '#f59e0b' : '#cbd5e1' }}; display: flex; align-items: center; justify-content: center;">
                                    @if($paymentMethod === 'paymint')
                                        <div style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b;"></div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        @if(! $this->canCreateOrRebuildApp())
                            <button disabled type="button" class="mam-btn-primary" style="background: #94a3b8; cursor: not-allowed;">
                                🔒 Unavailable on Current Plan
                            </button>
                        @elseif($paymentMethod === 'paymint')
                            <button wire:click="payWithPayMint" 
                                    wire:loading.attr="disabled"
                                    type="button" 
                                    class="mam-btn-primary mam-btn-amber">
                                <span wire:loading.remove wire:target="payWithPayMint">
                                    ⚡ Pay ₦{{ number_format($this->totalPrice, 0) }} with PayMint
                                </span>
                                <span wire:loading wire:target="payWithPayMint">
                                    Redirecting to Checkout...
                                </span>
                            </button>
                        @else
                            @if($walletBalance >= $this->totalPrice)
                                <button wire:click="orderWithBalance" 
                                        wire:loading.attr="disabled"
                                        type="button" 
                                        class="mam-btn-primary mam-btn-green">
                                    <span wire:loading.remove wire:target="orderWithBalance">
                                        💼 Pay ₦{{ number_format($this->totalPrice, 0) }} from Store Wallet
                                    </span>
                                    <span wire:loading wire:target="orderWithBalance">
                                        Debiting Wallet &amp; Compiling...
                                    </span>
                                </button>
                            @else
                                <div style="background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 10px; padding: 0.75rem; text-align: center;">
                                    <div style="font-size: 0.8rem; font-weight: 700; color: #dc2626; margin-bottom: 0.25rem;">
                                        Insufficient Store Balance (₦{{ number_format($walletBalance, 2) }})
                                    </div>
                                    <a href="{{ route('filament.merchant.pages.store-wallet', ['tenant' => $tenant->public_id]) }}" 
                                       style="font-size: 0.75rem; font-weight: 800; color: #d97706; text-decoration: underline;">
                                        Top up Store Wallet via Dedicated Account →
                                    </a>
                                </div>
                            @endif
                        @endif

                    @endif

                </div>

            </div>

        </div>

    </div>
</x-filament-panels::page>
