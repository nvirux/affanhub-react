<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $wallet = $tenant->mainWallet();
        $walletBalance = (float) ($wallet->balance ?? 0.00);
        $hasSufficient = $walletBalance >= $setupPrice;
        $app = $mobileApp;
        $isReady = $app && $app->isReady();
        $isBuilding = $app && $app->isBuilding();
        $hasPaid = $app && (float) $app->price_paid >= $setupPrice;
    @endphp

    <style>
        .app-manager-container {
            font-family: inherit;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
            box-sizing: border-box;
        }

        .app-light-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            box-sizing: border-box;
            position: relative;
        }

        .app-main-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .app-main-grid {
                grid-template-columns: 1.4fr 1fr;
            }
        }

        /* Realistic Smartphone Mockup */
        .phone-mockup-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem 1rem;
        }

        .phone-frame {
            width: 250px;
            height: 480px;
            background: #0f172a;
            border-radius: 40px;
            padding: 10px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            box-sizing: border-box;
        }

        .phone-screen {
            width: 100%;
            height: 100%;
            background: linear-gradient(160deg, #1e293b 0%, #0f172a 100%);
            border-radius: 32px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0.875rem 1rem 1.25rem 1rem;
            box-sizing: border-box;
            position: relative;
        }

        .phone-notch {
            width: 70px;
            height: 16px;
            background: #0f172a;
            border-radius: 20px;
            margin: 0 auto;
        }

        .phone-status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.65rem;
            font-weight: 700;
            color: #ffffff;
            opacity: 0.8;
            margin-top: 2px;
        }

        .phone-app-icon {
            width: 76px;
            height: 76px;
            border-radius: 18px;
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            object-fit: cover;
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: transform 0.2s ease;
        }

        .phone-app-icon:hover {
            transform: scale(1.05);
        }

        .phone-dock {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border-radius: 22px;
            padding: 0.5rem 0.75rem;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .phone-dock-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }

        .build-type-card {
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .build-type-card.is-selected {
            border-color: #10b981;
            background: #f0fdf4;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.12);
        }
    </style>

    {{-- Automatic Polling: when building, silently check GitHub every 7 seconds --}}
    <div class="app-manager-container" @if($isBuilding) wire:poll.7s="checkBuildStatus" @endif>

        {{-- Top App Status Header Card (Clean Light Aesthetic) --}}
        <div class="app-light-card">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem;">
                
                {{-- Left: Store Logo & Details --}}
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div style="width: 58px; height: 58px; border-radius: 16px; background: #f8fafc; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                        @if($iconChoice === 'custom' && $appIcon)
                            <img src="{{ $appIcon->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover;" />
                        @elseif($app && $app->app_icon_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($app->app_icon_path) }}" style="width: 100%; height: 100%; object-fit: cover;" />
                        @elseif($tenant->logo_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($tenant->logo_path) }}" style="width: 100%; height: 100%; object-fit: contain;" />
                        @else
                            <span style="font-size: 1.75rem;">📱</span>
                        @endif
                    </div>

                    <div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.01em;">
                                {{ $appName ?: $tenant->name }} Mobile App
                            </h2>

                            @if($isReady)
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;">
                                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981;"></span>
                                    v{{ $app->version_name }} Ready
                                </span>
                            @elseif($isBuilding)
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background-color: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
                                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #f59e0b; animation: pulse 1.5s infinite;"></span>
                                    Cloud Compiling ⏳
                                </span>
                            @else
                                <span style="padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background-color: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;">
                                    Setup Pending
                                </span>
                            @endif
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.4rem; flex-wrap: wrap;">
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

                {{-- Right: Direct Download & Actions --}}
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                    <button wire:click="checkBuildStatus" 
                            wire:loading.attr="disabled"
                            type="button"
                            style="padding: 0.65rem 1.1rem; background: #ffffff; border: 1.5px solid #d1d5db; color: #374151; font-size: 0.8125rem; font-weight: 700; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.15s ease;">
                        <span wire:loading.remove wire:target="checkBuildStatus">🔄 Check Status</span>
                        <span wire:loading wire:target="checkBuildStatus">Checking...</span>
                    </button>

                    @if($isReady)
                        {{-- PROXIED DIRECT APK DOWNLOAD ROUTE (NO GITHUB 401 ERRORS) --}}
                        <a href="{{ route('merchant.mobile-app.download', ['store' => $tenant->public_id]) }}" 
                           download
                           style="padding: 0.65rem 1.4rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; font-size: 0.875rem; font-weight: 800; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); transition: all 0.15s ease;">
                            <span>📥 Download APK (v{{ $app->version_name }})</span>
                        </a>
                    @endif
                </div>

            </div>

            {{-- If currently building, show clean progress alert --}}
            @if($isBuilding)
                <div style="margin-top: 1.25rem; padding: 1rem 1.25rem; background: #fffdf5; border: 1px solid #fef3c7; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 0.875rem;">
                        <div style="width: 22px; height: 22px; border: 2.5px solid #d97706; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; flex-shrink: 0;"></div>
                        <div>
                            <span style="font-size: 0.875rem; font-weight: 800; color: #92400e; display: block;">
                                Cloud Compiler Assembling v{{ $app->version_name }}...
                            </span>
                            <span style="font-size: 0.75rem; color: #b45309;">
                                Cloud runners are compiling your APK. This typically takes 60–90 seconds. This page auto-refreshes when complete.
                            </span>
                        </div>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #92400e; background: #fef3c7; padding: 0.25rem 0.6rem; border-radius: 6px;">
                        Auto-checking in background
                    </span>
                </div>
            @endif
        </div>

        {{-- Platform Eligibility & Availability Banners --}}
        @if(! $this->isBuilderEnabled())
            <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 16px; padding: 1.25rem 1.5rem; display: flex; align-items: flex-start; gap: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                <div style="font-size: 1.75rem;">⏸️</div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #991b1b; margin: 0;">Mobile App Creation Temporarily Paused</h4>
                    <p style="font-size: 0.8125rem; color: #b91c1c; margin-top: 0.35rem; margin-bottom: 0; line-height: 1.4;">
                        The platform administrators have temporarily paused new mobile app compilations. Previously compiled apps remain fully downloadable below.
                    </p>
                </div>
            </div>
        @elseif($this->requireCustomDomain() && ! $this->hasVerifiedCustomDomain())
            <div style="background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 16px; padding: 1.25rem 1.5rem; display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <div style="font-size: 1.75rem;">🌐</div>
                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #1e40af; margin: 0;">Verified Custom Domain Required</h4>
                        <p style="font-size: 0.8125rem; color: #1d4ed8; margin-top: 0.35rem; margin-bottom: 0; line-height: 1.4;">
                            To build your branded native Android app, your store must have a connected and verified custom domain (e.g. <code>yourbrand.com</code>).
                        </p>
                    </div>
                </div>
                <a href="{{ route('filament.merchant.resources.domains.index', ['tenant' => $tenant->public_id]) }}" 
                   style="background: #2563eb; color: white; padding: 0.65rem 1.25rem; border-radius: 10px; font-size: 0.8125rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; align-self: center;">
                    Connect Custom Domain &rarr;
                </a>
            </div>
        @elseif(! $this->isPlanAllowed())
            <div style="background: #fdf4ff; border: 1.5px solid #f0abfc; border-radius: 16px; padding: 1.25rem 1.5rem; display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <div style="font-size: 1.75rem;">⭐</div>
                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #86198f; margin: 0;">Subscription Plan Upgrade Required</h4>
                        <p style="font-size: 0.8125rem; color: #a21caf; margin-top: 0.35rem; margin-bottom: 0; line-height: 1.4;">
                            {{ $this->getEligibilityBlockReason() }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('filament.merchant.pages.billing', ['tenant' => $tenant->public_id]) }}" 
                   style="background: #9333ea; color: white; padding: 0.65rem 1.25rem; border-radius: 10px; font-size: 0.8125rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; align-self: center;">
                    Upgrade Plan &rarr;
                </a>
            </div>
        @endif

        {{-- Main 2-Column Grid --}}
        <div class="app-main-grid">
            
            {{-- Left Column: Customization & Build Stage Selector --}}
            <div class="app-light-card">
                
                {{-- Section Title --}}
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <div>
                        <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                            App Customization &amp; Release
                        </h3>
                        <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem; margin-bottom: 0;">
                            Configure your branding, test the APK, and generate the final release bundle.
                        </p>
                    </div>
                    @if($isBuilding)
                        <span style="background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 8px;">
                            🔒 Locked During Build
                        </span>
                    @endif
                </div>

                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    
                    {{-- Production Build Notice --}}
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.75rem; padding: 0.85rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
                        <div style="font-size: 1.25rem;">🛡️</div>
                        <div style="font-size: 0.8rem; color: #166534; line-height: 1.4;">
                            <strong>Official Signed Production Build:</strong> Compiles both a high-speed installable <strong>Android APK</strong> and a certified <strong>Google Play Store Bundle (.aab)</strong>, cryptographically signed for lifetime automatic updates.
                        </div>
                    </div>

                    {{-- 2. App Display Name --}}
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">
                            App Display Name <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" 
                               wire:model.live.debounce.300ms="appName" 
                               {{ $isBuilding ? 'disabled' : '' }}
                               placeholder="e.g. {{ $tenant->name }}" 
                               style="width: 100%; border: 1.5px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.9rem; color: #111827; outline: none; background-color: {{ $isBuilding ? '#f8fafc' : 'white' }}; cursor: {{ $isBuilding ? 'not-allowed' : 'text' }}; transition: border-color 0.2s;" />
                        <span style="font-size: 0.75rem; color: #6b7280; display: block; margin-top: 0.35rem;">
                            The title that appears directly under your app icon on your customers' Android screens.
                        </span>
                        @error('appName') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- 3. App Launcher Icon Section --}}
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">
                            App Launcher Icon <span style="color: #dc2626;">*</span>
                        </label>
                        
                        @if(! empty($tenant->logo_path))
                            {{-- Selection Tabs between Store Logo and Custom Icon --}}
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.875rem;">
                                <div wire:click="setIconChoice('store_logo')" 
                                     style="border: 1.5px solid {{ $iconChoice === 'store_logo' ? '#10b981' : '#e2e8f0' }}; background-color: {{ $iconChoice === 'store_logo' ? '#f0fdf4' : '#ffffff' }}; border-radius: 12px; padding: 0.875rem; cursor: {{ $isBuilding ? 'not-allowed' : 'pointer' }}; display: flex; align-items: center; gap: 0.75rem; transition: all 0.15s ease; opacity: {{ $isBuilding ? '0.7' : '1' }};">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; background: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($tenant->logo_path) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <span style="font-size: 0.8125rem; font-weight: 800; color: #111827; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            Use Store Logo
                                        </span>
                                        <span style="font-size: 0.7rem; color: #059669; font-weight: 600;">
                                            Auto 512×512 Square
                                        </span>
                                    </div>
                                    <div style="width: 16px; height: 16px; border-radius: 50%; border: 2px solid {{ $iconChoice === 'store_logo' ? '#10b981' : '#cbd5e1' }}; display: flex; align-items: center; justify-content: center;">
                                        @if($iconChoice === 'store_logo')
                                            <div style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></div>
                                        @endif
                                    </div>
                                </div>

                                <div wire:click="setIconChoice('custom')" 
                                     style="border: 1.5px solid {{ $iconChoice === 'custom' ? '#10b981' : '#e2e8f0' }}; background-color: {{ $iconChoice === 'custom' ? '#f0fdf4' : '#ffffff' }}; border-radius: 12px; padding: 0.875rem; cursor: {{ $isBuilding ? 'not-allowed' : 'pointer' }}; display: flex; align-items: center; gap: 0.75rem; transition: all 0.15s ease; opacity: {{ $isBuilding ? '0.7' : '1' }};">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; border: 1px dashed #cbd5e1; background: #f8fafc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem;">
                                        📁
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <span style="font-size: 0.8125rem; font-weight: 800; color: #111827; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            Upload Custom
                                        </span>
                                        <span style="font-size: 0.7rem; color: #64748b; font-weight: 600;">
                                            New Square File
                                        </span>
                                    </div>
                                    <div style="width: 16px; height: 16px; border-radius: 50%; border: 2px solid {{ $iconChoice === 'custom' ? '#10b981' : '#cbd5e1' }}; display: flex; align-items: center; justify-content: center;">
                                        @if($iconChoice === 'custom')
                                            <div style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Custom Upload Dropzone --}}
                        @if($iconChoice === 'custom' || empty($tenant->logo_path))
                            <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1rem; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                                @if($appIcon)
                                    <img src="{{ $appIcon->temporaryUrl() }}" style="width: 60px; height: 60px; border-radius: 16px; object-fit: cover; border: 2px solid #10b981;" />
                                @elseif($app && $app->app_icon_path)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($app->app_icon_path) }}" style="width: 60px; height: 60px; border-radius: 16px; object-fit: cover; border: 1px solid #e2e8f0;" />
                                @else
                                    <div style="width: 60px; height: 60px; border-radius: 16px; background-color: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: #94a3b8;">
                                        🖼️
                                    </div>
                                @endif

                                <div style="flex-grow: 1;">
                                    <input type="file" 
                                           wire:model="appIcon" 
                                           {{ $isBuilding ? 'disabled' : '' }}
                                           accept="image/png,image/jpeg,image/webp" 
                                           style="font-size: 0.8125rem; color: #475569;" />
                                    <span style="font-size: 0.75rem; color: #6b7280; display: block; margin-top: 0.35rem;">
                                        High-resolution image. Our system automatically formats it into a crisp 512×512 PNG without distortion.
                                    </span>
                                </div>
                            </div>
                            @error('appIcon') <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                        @else
                            <div style="background-color: #f0fdf4; border: 1px solid #dcfce7; border-radius: 12px; padding: 0.875rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 1.25rem;">✨</span>
                                <span style="font-size: 0.8125rem; color: #166534; line-height: 1.4;">
                                    Using your store logo. We will automatically format and center it onto a clean 512×512 square canvas with transparency.
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- 3. Google Play Store Publishing Scope --}}
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">
                            Publishing Scope
                        </label>
                        <div wire:click="togglePlaystore" 
                             style="border: 2px solid {{ $includePlaystore ? '#10b981' : '#e2e8f0' }}; background-color: {{ $includePlaystore ? '#f0fdf4' : '#ffffff' }}; border-radius: 14px; padding: 1rem 1.15rem; cursor: {{ ($app && $app->isPlayStoreRequested()) ? 'default' : 'pointer' }}; transition: all 0.2s ease;">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;">
                                <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                                    <div style="font-size: 1.6rem; margin-top: -0.15rem;">🚀</div>
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                            <span style="font-size: 0.9rem; font-weight: 800; color: #0f172a;">Include Google Play Store Submission</span>
                                            <span style="background: #e0e7ff; color: #3730a3; font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.55rem; border-radius: 9999px;">
                                                +₦{{ number_format($playstorePrice, 2) }}
                                            </span>
                                            @if($app && $app->isPlayStoreRequested())
                                                <span style="background: #ecfdf5; color: #059669; font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.55rem; border-radius: 9999px;">
                                                    ✓ {{ ucwords(str_replace('_', ' ', $app->playstore_status)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <p style="font-size: 0.75rem; color: #64748b; line-height: 1.4; margin-top: 0.35rem; margin-bottom: 0;">
                                            We generate, sign, and submit the certified <strong>Google Play Bundle (.aab)</strong> so your customers can download your app directly from the Google Play Store.
                                        </p>
                                    </div>
                                </div>
                                <div style="width: 20px; height: 20px; border-radius: 6px; border: 2px solid {{ $includePlaystore ? '#10b981' : '#cbd5e1' }}; background: {{ $includePlaystore ? '#10b981' : 'transparent' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 0.15rem;">
                                    @if($includePlaystore)
                                        <span style="color: white; font-weight: 900; font-size: 0.75rem;">✓</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rebuild or Building State Actions --}}
                    @if($hasPaid)
                        <div style="border-top: 1px solid #f1f5f9; padding-top: 1.25rem; margin-top: 0.5rem;">
                            @if($isBuilding)
                                <div style="background-color: #fffbeb; border: 1.5px solid #fef3c7; border-radius: 14px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.875rem;">
                                    <div style="width: 20px; height: 20px; border: 2.5px solid #d97706; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; flex-shrink: 0;"></div>
                                    <div>
                                        <span style="font-size: 0.875rem; font-weight: 800; color: #92400e; display: block;">
                                            Compilation in Progress (v{{ $app->version_name }})
                                        </span>
                                        <span style="font-size: 0.75rem; color: #b45309; line-height: 1.4; display: block; margin-top: 0.15rem;">
                                            Please wait for the current cloud build to complete before triggering another rebuild.
                                        </span>
                                    </div>
                                </div>
                            @else
                                @if(! $this->canCreateOrRebuildApp())
                                    <button disabled
                                            type="button"
                                            style="background: #94a3b8; color: white; font-weight: 800; font-size: 0.9rem; padding: 0.85rem 1.75rem; border: none; border-radius: 12px; cursor: not-allowed; display: inline-flex; align-items: center; gap: 0.5rem; opacity: 0.75;">
                                        🔒 Rebuild Locked
                                    </button>
                                    <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 0.45rem;">
                                        {{ $this->getEligibilityBlockReason() }}
                                    </span>
                                @else
                                    <button wire:click="requestUpdate" 
                                            wire:loading.attr="disabled"
                                            type="button"
                                            style="background: #0f172a; color: white; font-weight: 800; font-size: 0.9rem; padding: 0.85rem 1.75rem; border: none; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);">
                                        <span wire:loading.remove wire:target="requestUpdate">
                                            🔄 Rebuild {{ $buildType === 'release' ? 'Production Release' : 'Test APK' }} (v{{ $app->version_code + 1 }}.0)
                                        </span>
                                        <span wire:loading wire:target="requestUpdate">
                                            Dispatching Cloud Rebuild...
                                        </span>
                                    </button>
                                    <span style="font-size: 0.75rem; color: #64748b; display: block; margin-top: 0.45rem;">
                                        Rebuilds are free. The build version will automatically increment to v{{ $app->version_code + 1 }}.0.
                                    </span>
                                @endif
                            @endif
                        </div>
                    @endif

                </div>
            </div>

            {{-- Right Column: Realistic Smartphone Mockup or Payment Options --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                {{-- Payment Panel (Only shown before first payment) --}}
                @if(! $hasPaid)
                    <div class="app-light-card">
                        <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">
                            Payment &amp; Setup
                        </h3>
                        <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem; margin-bottom: 1.25rem;">
                            One-time lifetime setup fee for cloud compilation and APK generation.
                        </p>

                        {{-- Order Price Summary Card --}}
                        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem; margin-bottom: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8125rem; color: #64748b; margin-bottom: 0.5rem;">
                                <span>Base Android APK Build</span>
                                <span style="font-weight: 700; color: #111827;">₦{{ number_format($setupPrice, 2) }}</span>
                            </div>
                            @if($includePlaystore)
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8125rem; color: #64748b; margin-bottom: 0.5rem;">
                                    <span>Google Play Store Publishing</span>
                                    <span style="font-weight: 700; color: #15803d;">+₦{{ number_format($playstorePrice, 2) }}</span>
                                </div>
                            @endif
                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #cbd5e1; padding-top: 0.75rem;">
                                <span style="font-size: 0.95rem; font-weight: 700; color: #1e293b;">Total Investment</span>
                                <span style="font-size: 1.35rem; font-weight: 900; color: #d97706;">₦{{ number_format($this->totalPrice, 2) }}</span>
                            </div>
                        </div>

                        {{-- Payment Method Selection Tabs --}}
                        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.25rem;">
                            <div wire:click="setPaymentMethod('paymint')" 
                                 class="payment-tab-btn {{ $paymentMethod === 'paymint' ? 'is-active' : '' }}">
                                <div style="font-size: 1.4rem;">⚡</div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 0.875rem; font-weight: 800; color: #111827;">Pay with PayMint</span>
                                        <span style="font-size: 0.7rem; font-weight: 700; background: #ecfdf5; color: #059669; padding: 0.15rem 0.5rem; border-radius: 9999px;">
                                            Instant
                                        </span>
                                    </div>
                                    <span style="font-size: 0.75rem; color: #64748b; display: block; margin-top: 0.2rem;">
                                        Transfer, Card, or USSD
                                    </span>
                                </div>
                                <div style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid {{ $paymentMethod === 'paymint' ? '#f59e0b' : '#cbd5e1' }}; display: flex; align-items: center; justify-content: center;">
                                    @if($paymentMethod === 'paymint')
                                        <div style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b;"></div>
                                    @endif
                                </div>
                            </div>

                            <div wire:click="setPaymentMethod('wallet')" 
                                 class="payment-tab-btn {{ $paymentMethod === 'wallet' ? 'is-active' : '' }}">
                                <div style="font-size: 1.4rem;">💼</div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 0.875rem; font-weight: 800; color: #111827;">Pay from Store Balance</span>
                                        <span style="font-size: 0.75rem; font-weight: 800; color: {{ $walletBalance >= $this->totalPrice ? '#059669' : '#dc2626' }};">
                                            ₦{{ number_format($walletBalance, 2) }}
                                        </span>
                                    </div>
                                    <span style="font-size: 0.75rem; color: #64748b; display: block; margin-top: 0.2rem;">
                                        Auto-deducted from main wallet
                                    </span>
                                </div>
                                <div style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid {{ $paymentMethod === 'wallet' ? '#f59e0b' : '#cbd5e1' }}; display: flex; align-items: center; justify-content: center;">
                                    @if($paymentMethod === 'wallet')
                                        <div style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b;"></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        @if(! $this->canCreateOrRebuildApp())
                            <div style="background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 12px; padding: 1rem; text-align: center;">
                                <div style="font-size: 0.875rem; font-weight: 800; color: #64748b; display: flex; align-items: center; justify-content: center; gap: 0.35rem;">
                                    <span>🔒</span>
                                    <span>Ordering Currently Unavailable</span>
                                </div>
                                <span style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.35rem; display: block; line-height: 1.4;">
                                    {{ $this->getEligibilityBlockReason() }}
                                </span>
                            </div>
                        @elseif($paymentMethod === 'paymint')
                            <button wire:click="payWithPayMint" 
                                    wire:loading.attr="disabled"
                                    type="button" 
                                    style="width: 100%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; font-weight: 800; font-size: 0.95rem; padding: 0.95rem; border: none; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                                <span wire:loading.remove wire:target="payWithPayMint">
                                    ⚡ Pay ₦{{ number_format($this->totalPrice, 0) }} with PayMint
                                </span>
                                <span wire:loading wire:target="payWithPayMint">
                                    Connecting to PayMint Gateway...
                                </span>
                            </button>
                        @else
                            @if($walletBalance >= $this->totalPrice)
                                <button wire:click="orderWithBalance" 
                                        wire:loading.attr="disabled"
                                        type="button" 
                                        style="width: 100%; background: #059669; color: white; font-weight: 800; font-size: 0.95rem; padding: 0.95rem; border: none; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);">
                                    <span wire:loading.remove wire:target="orderWithBalance">
                                        💼 Pay ₦{{ number_format($this->totalPrice, 0) }} from Store Wallet
                                    </span>
                                    <span wire:loading wire:target="orderWithBalance">
                                        Debiting Wallet &amp; Compiling...
                                    </span>
                                </button>
                            @else
                                <div style="background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 12px; padding: 0.875rem; text-align: center;">
                                    <div style="font-size: 0.8125rem; font-weight: 700; color: #dc2626; margin-bottom: 0.35rem;">
                                        Insufficient Store Balance (₦{{ number_format($walletBalance, 2) }})
                                    </div>
                                    <a href="{{ route('filament.merchant.pages.store-wallet', ['tenant' => $tenant->public_id]) }}" 
                                       style="font-size: 0.75rem; font-weight: 800; color: #d97706; text-decoration: underline;">
                                        Top up Store Wallet via Dedicated Account →
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif

                {{-- Google Play Store Publishing Card (For already built apps) --}}
                @if($hasPaid && ! $app->isPlayStoreRequested())
                    <div class="app-light-card" style="border: 1.5px solid #bbf7d0; background: #f0fdf4;">
                        <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                            <div style="font-size: 1.8rem;">🚀</div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 1rem; font-weight: 800; color: #14532d; margin: 0;">
                                    Publish to Google Play Store
                                </h4>
                                <p style="font-size: 0.8rem; color: #15803d; line-height: 1.4; margin-top: 0.35rem; margin-bottom: 0.85rem;">
                                    Make your store app searchable and downloadable on Google Play! Our deployment team will package, cryptographically sign, and submit your official app bundle (.aab) to Google Play for a one-time fee of <strong>₦{{ number_format($playstorePrice, 2) }}</strong>.
                                </p>
                                <button wire:click="requestPlayStorePublishing" 
                                        wire:loading.attr="disabled"
                                        type="button" 
                                        style="background: #15803d; color: white; padding: 0.75rem 1.4rem; border-radius: 10px; font-weight: 800; font-size: 0.85rem; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px -1px rgba(21, 128, 61, 0.2);">
                                    <span wire:loading.remove wire:target="requestPlayStorePublishing">
                                        Request Google Play Publishing (₦{{ number_format($playstorePrice, 2) }})
                                    </span>
                                    <span wire:loading wire:target="requestPlayStorePublishing">
                                        Submitting Request...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @elseif($hasPaid && $app->isPlayStoreRequested())
                    <div class="app-light-card" style="border: 1.5px solid #c7d2fe; background: #eef2ff;">
                        <div style="display: flex; align-items: center; gap: 0.85rem;">
                            <div style="font-size: 1.8rem;">✨</div>
                            <div>
                                <span style="font-size: 0.875rem; font-weight: 800; color: #3730a3; display: block;">
                                    Google Play Store: {{ ucwords(str_replace('_', ' ', $app->playstore_status)) }}
                                </span>
                                <span style="font-size: 0.75rem; color: #4338ca; line-height: 1.4; display: block; margin-top: 0.2rem;">
                                    @if($app->isPlayStorePublished())
                                        Your app is live! <a href="{{ $app->playstore_url }}" target="_blank" style="text-decoration: underline; font-weight: 800;">View on Google Play Store →</a>
                                    @else
                                        Our deployment engineers are packaging and submitting your official app bundle (.aab) to the Google Play Console.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Realistic Smartphone Live Home Screen Mockup --}}
                <div class="app-light-card" style="display: flex; flex-direction: column; align-items: center;">
                    <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 1rem;">
                        Phone Home Screen Preview
                    </span>

                    <div class="phone-mockup-wrapper">
                        <div class="phone-frame">
                            <div class="phone-screen">
                                {{-- Status Bar --}}
                                <div>
                                    <div class="phone-notch"></div>
                                    <div class="phone-status-bar">
                                        <span>9:41</span>
                                        <span>📶 5G 🔋</span>
                                    </div>
                                </div>

                                {{-- Centered App Launcher Icon --}}
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; margin: 1.5rem 0;">
                                    @if($iconChoice === 'custom' && $appIcon)
                                        <img src="{{ $appIcon->temporaryUrl() }}" class="phone-app-icon" />
                                    @elseif($iconChoice === 'store_logo' && $tenant->logo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($tenant->logo_path) }}" class="phone-app-icon" />
                                    @elseif($app && $app->app_icon_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($app->app_icon_path) }}" class="phone-app-icon" />
                                    @elseif($tenant->logo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($tenant->logo_path) }}" class="phone-app-icon" />
                                    @else
                                        <div class="phone-app-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; font-size: 2rem;">
                                            📱
                                        </div>
                                    @endif

                                    <span style="margin-top: 0.65rem; font-size: 0.8rem; font-weight: 700; color: #ffffff; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-shadow: 0 1px 4px rgba(0,0,0,0.8);">
                                        {{ $appName ?: ($tenant->name ?? 'My App') }}
                                    </span>
                                </div>

                                {{-- Bottom App Dock --}}
                                <div class="phone-dock">
                                    <div class="phone-dock-icon">📞</div>
                                    <div class="phone-dock-icon">💬</div>
                                    <div class="phone-dock-icon">🌐</div>
                                    <div class="phone-dock-icon">⚙️</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span style="font-size: 0.75rem; color: #94a3b8; text-align: center; margin-top: 0.5rem;">
                        Exact appearance of your launcher icon on customer Android devices.
                    </span>
                </div>

            </div>

        </div>

    </div>
</x-filament-panels::page>
