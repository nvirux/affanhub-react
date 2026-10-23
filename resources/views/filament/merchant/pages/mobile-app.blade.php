<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $app = $mobileApp;
        $isReady = $app && $app->isReady();
        $isBuilding = $app && $app->isBuilding();
        $downloadUrl = $app ? $app->getDownloadUrl() : route('merchant.mobile-app.download', ['store' => $tenant->public_id]);
    @endphp

    <style>
        .app-builder-wrap {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .ab-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03);
            box-sizing: border-box;
        }

        @media (max-width: 640px) {
            .ab-card {
                padding: 1.15rem;
                border-radius: 14px;
            }
            .hero-actions {
                width: 100%;
                flex-direction: column;
            }
            .hero-actions a,
            .hero-actions button {
                width: 100%;
                justify-content: center;
            }
        }

        .ab-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .ab-badge-green {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .ab-badge-amber {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .ab-badge-slate {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .ab-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            font-weight: 700;
            font-size: 0.875rem;
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            box-sizing: border-box;
        }

        .ab-btn-primary {
            background: #0f172a;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);
        }

        .ab-btn-primary:hover {
            background: #1e293b;
        }

        .ab-btn-green {
            background: #10b981;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .ab-btn-green:hover {
            background: #059669;
        }

        .ab-btn-outline {
            background: #ffffff;
            color: #334155;
            border: 1.5px solid #cbd5e1;
        }

        .ab-btn-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .ab-btn[disabled],
        button[disabled] {
            opacity: 0.6;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .ab-input {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.7rem 0.95rem;
            font-size: 0.9rem;
            background: #ffffff;
            color: #0f172a;
            box-sizing: border-box;
            transition: border-color 0.15s ease;
        }

        .ab-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .icon-toggle-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 700;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .icon-toggle-pill.active {
            border-color: #0f172a;
            background: #0f172a;
            color: #ffffff;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

    <div class="app-builder-wrap" @if($isBuilding) wire:poll.7s="checkBuildStatus" @endif>

        {{-- 1. Hero Status Card --}}
        <div class="ab-card" style="border: 1.5px solid {{ $isReady ? '#a7f3d0' : ($isBuilding ? '#fde68a' : '#e2e8f0') }}; background: {{ $isReady ? 'linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%)' : '#ffffff' }};">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; flex-wrap: wrap;">
                
                {{-- App Identity Summary --}}
                <div style="display: flex; align-items: center; gap: 1rem; min-width: 0;">
                    <div style="width: 60px; height: 60px; border-radius: 16px; background: #f8fafc; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        @if($iconChoice === 'custom' && $appIcon)
                            <img src="{{ $appIcon->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover;" alt="App Icon" />
                        @elseif($app && $app->app_icon_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($app->app_icon_path) }}" 
                                 onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';"
                                 style="width: 100%; height: 100%; object-fit: cover;" alt="App Icon" />
                            <span style="display: none; font-size: 1.75rem;">📱</span>
                        @elseif($tenant->logo_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($tenant->logo_path) }}" 
                                 onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';"
                                 style="width: 100%; height: 100%; object-fit: contain;" alt="App Icon" />
                            <span style="display: none; font-size: 1.75rem;">📱</span>
                        @else
                            <span style="font-size: 1.75rem;">📱</span>
                        @endif
                    </div>

                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <h2 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.01em;">
                                {{ $appName ?: $tenant->name }}
                            </h2>

                            @if($isReady)
                                <span class="ab-badge ab-badge-green">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span>
                                    v{{ $app->version_name }} Ready
                                </span>
                            @elseif($isBuilding)
                                <span class="ab-badge ab-badge-amber">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; animation: pulse 1s infinite;"></span>
                                    Compiling APK (~90s)
                                </span>
                            @else
                                <span class="ab-badge ab-badge-slate">
                                    Not Compiled Yet
                                </span>
                            @endif
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.35rem; flex-wrap: wrap;">
                            <code style="font-family: monospace; font-size: 0.75rem; font-weight: 700; color: #0284c7; background: #f0f9ff; padding: 0.15rem 0.45rem; border-radius: 6px; border: 1px solid #bae6fd;">
                                {{ $this->package_id }}
                            </code>
                            @if($app && $app->last_built_at)
                                <span style="font-size: 0.75rem; color: #94a3b8;">
                                    • Built {{ $app->last_built_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="hero-actions" style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <button wire:click="checkBuildStatus" 
                            wire:loading.attr="disabled"
                            type="button" 
                            class="ab-btn ab-btn-outline" 
                            style="font-size: 0.8125rem; padding: 0.6rem 0.9rem;"
                            title="Check build status on GitHub">
                        <span wire:loading.remove wire:target="checkBuildStatus">🔄 Check Status</span>
                        <span wire:loading.inline-flex wire:target="checkBuildStatus" style="display: none; align-items: center; gap: 0.3rem;">
                            <span style="display: inline-block; animation: spin 1s linear infinite;">⏳</span> Checking...
                        </span>
                    </button>

                    @if($isReady)
                        <a href="{{ $downloadUrl }}" 
                           download
                           class="ab-btn ab-btn-green"
                           style="padding: 0.6rem 1.15rem;">
                            <span>📥 Download APK</span>
                        </a>

                        <div x-data="{ copied: false }">
                            <button type="button"
                                    x-on:click="navigator.clipboard.writeText('{{ $downloadUrl }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                    class="ab-btn ab-btn-outline"
                                    style="padding: 0.6rem 0.9rem; font-size: 0.8125rem;">
                                <span x-show="!copied">📋 Copy Link</span>
                                <span x-show="copied" style="display: none; color: #166534;">✓ Copied</span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Building Progress Bar --}}
            @if($isBuilding)
                <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px dashed #fde68a; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <div style="width: 18px; height: 18px; border: 2.5px solid #d97706; border-top-color: transparent; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                        <span style="font-size: 0.8125rem; font-weight: 700; color: #92400e;">
                            Assembling your Android APK in the cloud. This page automatically updates when ready.
                        </span>
                    </div>

                    <button wire:click="cancelBuild" 
                            wire:confirm="Cancel current build and allow retry?"
                            type="button" 
                            style="font-size: 0.75rem; font-weight: 700; color: #dc2626; background: #ffffff; border: 1px solid #fca5a5; padding: 0.3rem 0.65rem; border-radius: 6px; cursor: pointer;">
                        Cancel Build
                    </button>
                </div>
            @endif
        </div>

        {{-- 2. Push Notifications Banner (Clean, low-profile) --}}
        @if($isReady)
            <div class="ab-card" style="border: 1px solid #bfdbfe; background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">🔔</span>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <strong style="font-size: 0.95rem; color: #0f172a;">App Push Notifications Hub</strong>
                            <span class="ab-badge ab-badge-green" style="padding: 0.1rem 0.45rem; font-size: 0.68rem;">OneSignal Cloud</span>
                        </div>
                        <p style="font-size: 0.775rem; color: #64748b; margin: 0.15rem 0 0 0;">
                            Send promos, flash sales, and deep links (Data, Airtime, Wallet) to customers with your app.
                        </p>
                    </div>
                </div>

                <a href="{{ route('filament.merchant.pages.push-notifications', ['tenant' => $tenant->public_id]) }}" 
                   class="ab-btn ab-btn-primary" 
                   style="padding: 0.55rem 1.15rem; font-size: 0.8125rem;">
                    <span>📢 Compose Push Broadcast &rarr;</span>
                </a>
            </div>
        @endif

        {{-- 3. App Branding & Configuration Form --}}
        <div class="ab-card">
            <div style="margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;">
                    App Branding &amp; Details
                </h3>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0.2rem 0 0 0;">
                    Customize how your Android app appears on your customers' phone home screens.
                </p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                {{-- App Name --}}
                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 700; color: #334155; margin-bottom: 0.35rem;">
                        App Name <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" 
                           wire:model="appName" 
                           placeholder="e.g. {{ $tenant->name }}" 
                           maxlength="30"
                           @if($isBuilding) disabled @endif
                           class="ab-input" />
                    @error('appName') <span style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                </div>

                {{-- Launcher Icon Option --}}
                <div>
                    <label style="display: block; font-size: 0.8125rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem;">
                        App Launcher Icon
                    </label>

                    <div style="display: flex; gap: 0.5rem; margin-bottom: 0.85rem; flex-wrap: wrap;">
                        <button type="button" 
                                wire:click="setIconChoice('store_logo')" 
                                class="icon-toggle-pill {{ $iconChoice === 'store_logo' ? 'active' : '' }}">
                            <span>🏪</span>
                            <span>Use Store Logo</span>
                        </button>

                        <button type="button" 
                                wire:click="setIconChoice('custom')" 
                                class="icon-toggle-pill {{ $iconChoice === 'custom' ? 'active' : '' }}">
                            <span>✨</span>
                            <span>Custom App Icon</span>
                        </button>
                    </div>

                    @if($iconChoice === 'custom')
                        <div style="background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 1.25rem; text-align: center; cursor: pointer;"
                             onclick="document.getElementById('ab_app_icon_input').click()">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 0.35rem;">
                                <span style="font-size: 1.5rem;">📤</span>
                                <span style="font-size: 0.8125rem; font-weight: 700; color: #0f172a;">
                                    Click to choose square icon image
                                </span>
                                <span style="font-size: 0.72rem; color: #64748b;">
                                    PNG, JPG, or WebP (Square 512x512 recommended, max 3MB)
                                </span>
                            </div>
                            <input type="file" 
                                   id="ab_app_icon_input" 
                                   wire:model="appIcon" 
                                   accept="image/png,image/jpeg,image/webp" 
                                   style="display: none;" />
                        </div>
                        @error('appIcon') <span style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                    @endif
                </div>

                {{-- Storefront Target Note --}}
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.65rem 0.85rem; font-size: 0.75rem; color: #64748b; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                    <span>Native WebView Target:</span>
                    <code style="font-family: monospace; font-weight: 700; color: #0284c7;">
                        {{ $this->getResolvedStoreUrl() }}
                    </code>
                </div>

            </div>
        </div>

        {{-- 4. Distribution & Play Store Card --}}
        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;" class="dist-grid">
            
            {{-- Direct APK Card --}}
            <div class="ab-card" style="border: 1.5px solid #10b981; background: #f0fdf4;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;">
                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <span style="font-size: 1.35rem;">📦</span>
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                <strong style="font-size: 0.875rem; color: #065f46;">Branded Android APK</strong>
                                <span class="ab-badge ab-badge-green" style="font-size: 0.68rem; padding: 0.1rem 0.45rem;">FREE &amp; INCLUDED</span>
                            </div>
                            <p style="font-size: 0.75rem; color: #047857; margin: 0.2rem 0 0 0; line-height: 1.4;">
                                Instantly compile and share a high-performance Android APK with your store customers via direct link or QR code.
                            </p>
                        </div>
                    </div>
                    <span style="color: #10b981; font-weight: 900; font-size: 1.1rem;">✓</span>
                </div>
            </div>

            {{-- Google Play Store Card --}}
            <div class="ab-card" style="border: 1px solid #e2e8f0; background: #ffffff;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <span style="font-size: 1.35rem;">🚀</span>
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;">
                                <strong style="font-size: 0.875rem; color: #0f172a;">Google Play Store Publishing</strong>
                                @if($app && $app->isPlayStorePublished())
                                    <span class="ab-badge ab-badge-green" style="font-size: 0.68rem; padding: 0.1rem 0.45rem;">✓ Live on Google Play Store</span>
                                @elseif($app && $app->isPlayStoreRequested())
                                    <span class="ab-badge ab-badge-amber" style="font-size: 0.68rem; padding: 0.1rem 0.45rem;">⏳ In Review</span>
                                @else
                                    <span class="ab-badge ab-badge-slate" style="font-size: 0.68rem; padding: 0.1rem 0.45rem;">Concierge Publishing</span>
                                @endif
                            </div>
                            <p style="font-size: 0.75rem; color: #64748b; margin: 0.2rem 0 0 0; line-height: 1.4;">
                                Want your app on the Google Play Store? Our team handles app bundle signing, screenshots, and Google compliance.
                            </p>
                        </div>
                    </div>

                    <a href="https://wa.me/2348000000000?text={{ urlencode('Hello AffanHub Admin, I want to publish my mobile app on Google Play Store: ' . $tenant->name) }}"
                       target="_blank"
                       class="ab-btn ab-btn-outline" 
                       style="font-size: 0.75rem; padding: 0.45rem 0.85rem; white-space: nowrap;">
                        💬 Chat with Publishing Team &rarr;
                    </a>
                </div>
            </div>

        </div>

        {{-- 5. Primary Compilation / Action Button --}}
        <div class="ab-card" style="text-align: center;">
            @if(! $this->canCreateOrRebuildApp())
                <button disabled type="button" class="ab-btn ab-btn-primary" style="width: 100%; max-width: 420px; margin: 0 auto;">
                    🔒 Compilation Unavailable
                </button>
                <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 0.35rem;">
                    {{ $this->getEligibilityBlockReason() }}
                </span>
            @elseif($isBuilding)
                <button disabled type="button" class="ab-btn ab-btn-primary" style="width: 100%; max-width: 420px; margin: 0 auto; background: #d97706; cursor: wait;">
                    ⏳ Compiling in Cloud...
                </button>
                <span style="font-size: 0.75rem; color: #92400e; display: block; margin-top: 0.35rem;">
                    Please wait while your APK is generated.
                </span>
            @elseif($isReady)
                @if($app->isPlayStorePublished())
                    <div style="max-width: 460px; margin: 0 auto; text-align: center;">
                        <span style="display: inline-block; font-size: 0.8125rem; font-weight: 700; color: #166534; background: #f0fdf4; border: 1.5px solid #bbf7d0; padding: 0.6rem 1rem; border-radius: 10px; margin-bottom: 0.75rem;">
                            🛡️ App is Live on Google Play Store
                        </span>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0 0 0.75rem 0;">
                            Automatic self-serve rebuilds are locked to avoid version code mismatches with Google Play.
                        </p>
                        <a href="https://wa.me/2348000000000?text={{ urlencode('Hello AffanHub Admin, I need an updated Google Play build for: ' . $tenant->name) }}" 
                           target="_blank"
                           class="ab-btn ab-btn-outline" 
                           style="font-size: 0.8125rem;">
                            💬 Contact Admin for Store Update
                        </a>
                    </div>
                @else
                    <button wire:click="requestUpdate" 
                            wire:loading.attr="disabled"
                            type="button" 
                            class="ab-btn ab-btn-green"
                            style="width: 100%; max-width: 420px; margin: 0 auto; font-size: 0.95rem; padding: 0.85rem 1.5rem;">
                        <span wire:loading.remove wire:target="requestUpdate">
                            🔄 Rebuild Update (v{{ $app->version_code + 1 }}.0 - Free)
                        </span>
                        <span wire:loading.inline-flex wire:target="requestUpdate" style="display: none; align-items: center; gap: 0.35rem;">
                            <span style="display: inline-block; animation: spin 1s linear infinite;">⏳</span> Dispatching Cloud Update...
                        </span>
                    </button>
                    <span style="font-size: 0.75rem; color: #64748b; display: block; margin-top: 0.4rem;">
                        Re-compiles your app with any new name or icon adjustments.
                    </span>
                @endif
            @else
                <button wire:click="generateFreeApp" 
                        wire:loading.attr="disabled"
                        type="button" 
                        class="ab-btn ab-btn-green"
                        style="width: 100%; max-width: 420px; margin: 0 auto; font-size: 0.95rem; padding: 0.85rem 1.5rem;">
                    <span wire:loading.remove wire:target="generateFreeApp">
                        🚀 Generate Android App (Free)
                    </span>
                    <span wire:loading.inline-flex wire:target="generateFreeApp" style="display: none; align-items: center; gap: 0.35rem;">
                        <span style="display: inline-block; animation: spin 1s linear infinite;">⏳</span> Starting Cloud Build...
                    </span>
                </button>
                <span style="font-size: 0.75rem; color: #64748b; display: block; margin-top: 0.4rem;">
                    Compiles your custom Android APK in approximately 2 minutes. Free &amp; included.
                </span>
            @endif
        </div>

    </div>
</x-filament-panels::page>
