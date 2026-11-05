<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $app = $mobileApp;
        $isReady = $app && $app->isReady();
        $isBuilding = $app && $app->isBuilding();
        $downloadUrl = $app ? $app->getDownloadUrl() : route('merchant.mobile-app.download', ['store' => $tenant->public_id]);
        $announcementsUrl = route('filament.merchant.resources.announcements.index', ['tenant' => $tenant->public_id, 'tab' => 'push']);
    @endphp

    <style>
        .app-manager-container {
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            box-sizing: border-box;
        }

        .mam-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
            box-sizing: border-box;
        }

        :is(.dark .mam-card) {
            background: #0f172a;
            border-color: #1e293b;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.25);
        }

        @media (max-width: 640px) {
            .mam-card {
                padding: 1.15rem;
                border-radius: 14px;
            }
            .mam-hero-actions {
                width: 100%;
                flex-direction: column;
            }
            .mam-hero-actions a,
            .mam-hero-actions button {
                width: 100%;
                justify-content: center;
            }
        }

        .mam-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .mam-badge-green {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        :is(.dark .mam-badge-green) {
            background: rgba(6, 95, 70, 0.25);
            color: #34d399;
            border-color: rgba(52, 211, 153, 0.3);
        }

        .mam-badge-amber {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        :is(.dark .mam-badge-amber) {
            background: rgba(146, 64, 14, 0.25);
            color: #fbbf24;
            border-color: rgba(251, 191, 36, 0.3);
        }

        .mam-badge-slate {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        :is(.dark .mam-badge-slate) {
            background: #1e293b;
            color: #94a3b8;
            border-color: #334155;
        }

        .mam-btn {
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
            line-height: 1.25;
        }

        .mam-btn-primary {
            background: #0f172a;
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.12);
        }
        .mam-btn-primary:hover {
            background: #1e293b;
        }
        :is(.dark .mam-btn-primary) {
            background: #3b82f6;
            color: #ffffff;
        }
        :is(.dark .mam-btn-primary:hover) {
            background: #2563eb;
        }

        .mam-btn-green {
            background: #10b981;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
        }
        .mam-btn-green:hover {
            background: #059669;
        }

        .mam-btn-outline {
            background: #ffffff;
            color: #334155;
            border: 1.5px solid #cbd5e1;
        }
        .mam-btn-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }
        :is(.dark .mam-btn-outline) {
            background: #1e293b;
            color: #f1f5f9;
            border-color: #334155;
        }
        :is(.dark .mam-btn-outline:hover) {
            background: #334155;
            border-color: #475569;
        }

        .mam-btn[disabled],
        button[disabled] {
            opacity: 0.6;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .mam-input {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.7rem 0.95rem;
            font-size: 0.9rem;
            background: #ffffff;
            color: #0f172a;
            box-sizing: border-box;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .mam-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
        :is(.dark .mam-input) {
            background: #0b1120;
            color: #f8fafc;
            border-color: #334155;
        }
        :is(.dark .mam-input:focus) {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        @keyframes mam-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

    <div class="app-manager-container" @if($isBuilding) wire:poll.7s="checkBuildStatus" @endif>

        {{-- 1. Hero App Status Card --}}
        <div class="mam-card" style="border: 1px solid {{ $isReady ? '#86efac' : ($isBuilding ? '#fde68a' : '#e2e8f0') }};">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; flex-wrap: wrap;">
                
                {{-- App Identity Summary --}}
                <div style="display: flex; align-items: center; gap: 1.15rem; min-width: 0;">
                    <div style="position: relative; width: 64px; height: 64px; border-radius: 16px; background: #f8fafc; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.04);" class="dark:bg-gray-800 dark:border-gray-700">
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
                            <span style="font-size: 1.85rem;">📱</span>
                        @endif

                        {{-- Spinner while icon is uploading --}}
                        <div wire:loading.flex wire:target="appIcon" style="display: none; position: absolute; inset: 0; background: rgba(15, 23, 42, 0.65); align-items: center; justify-content: center; z-index: 10;">
                            <span style="display: inline-block; animation: mam-spin 1s linear infinite; font-size: 1.25rem;">⏳</span>
                        </div>
                    </div>

                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                            <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.015em;" class="dark:text-white">
                                {{ $appName ?: ($app ? $app->app_name : 'New Android App') }}
                            </h2>

                            @if($isReady)
                                <span class="mam-badge mam-badge-green">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span>
                                    v{{ $app->version_name }} Ready
                                </span>
                            @elseif($isBuilding)
                                <span class="mam-badge mam-badge-amber">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; animation: pulse 1s infinite;"></span>
                                    Compiling APK (~90s)
                                </span>
                            @else
                                <span class="mam-badge mam-badge-slate">
                                    Not Built Yet
                                </span>
                            @endif
                        </div>

                        {{-- Only show package ID after app is created/building/ready --}}
                        @if($app && $app->package_id)
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.4rem; flex-wrap: wrap;">
                                <code style="font-family: monospace; font-size: 0.78rem; font-weight: 700; color: #0284c7; background: #f0f9ff; padding: 0.15rem 0.5rem; border-radius: 6px; border: 1px solid #bae6fd;" class="dark:bg-sky-950 dark:border-sky-800 dark:text-sky-300">
                                    {{ $app->package_id }}
                                </code>
                                @if($app->last_built_at)
                                    <span style="font-size: 0.78rem; color: #64748b;" class="dark:text-gray-400">
                                        • Built {{ $app->last_built_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mam-hero-actions" style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                    {{-- Only display Check Status when a build is actively compiling --}}
                    @if($isBuilding)
                        <button wire:click="checkBuildStatus" 
                                wire:loading.attr="disabled"
                                type="button" 
                                class="mam-btn mam-btn-outline" 
                                style="font-size: 0.8125rem; padding: 0.6rem 0.95rem;"
                                title="Check build status on GitHub">
                            <span wire:loading.remove wire:target="checkBuildStatus">🔄 Check Status</span>
                            <span wire:loading.inline-flex wire:target="checkBuildStatus" style="display: none; align-items: center; gap: 0.35rem;">
                                <span style="display: inline-block; animation: mam-spin 1s linear infinite;">⏳</span> Checking...
                            </span>
                        </button>
                    @endif

                    @if($isReady)
                        <a href="{{ $downloadUrl }}" 
                           download
                           class="mam-btn mam-btn-green"
                           style="padding: 0.6rem 1.25rem;">
                            <span>📥 Download APK</span>
                        </a>

                        <div x-data="{ copied: false }">
                            <button type="button"
                                    x-on:click="navigator.clipboard.writeText('{{ $downloadUrl }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                    class="mam-btn mam-btn-outline" 
                                    style="padding: 0.6rem 0.95rem; font-size: 0.8125rem;">
                                <span x-show="!copied">📋 Copy Link</span>
                                <span x-show="copied" style="display: none; color: #166534;" class="dark:text-emerald-400">✓ Copied</span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Google Play Publishing Notice (Inside First Card) --}}
            <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;" class="dark:border-gray-800">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 1.15rem;">🌐</span>
                    <span style="font-size: 0.8125rem; color: #475569;" class="dark:text-gray-300">
                        We also support <strong>Google Play Store publishing</strong> upon request if you'd like your app listed on Google Play.
                    </span>
                </div>

                <a href="https://wa.me/2348000000000?text={{ urlencode('Hello AffanHub Admin, I want to publish my mobile app on Google Play Store: ' . $tenant->name) }}"
                   target="_blank"
                   style="font-size: 0.8125rem; font-weight: 700; color: #2563eb; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;"
                   class="dark:text-sky-400 hover:underline">
                    <span>Inquire on WhatsApp</span> &rarr;
                </a>
            </div>
        </div>

        @if($isBuilding)
            {{-- Focused Cloud Compilation Tracker (Replaces editable forms while in progress) --}}
            <div class="mam-card" style="border: 1.5px solid #fde68a; background: linear-gradient(180deg, #fffdf8 0%, #ffffff 100%); padding: 2.25rem 1.5rem; text-align: center;" class="dark:bg-gray-900/90 dark:border-amber-900/60">
                <div style="max-width: 500px; margin: 0 auto; display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                    
                    {{-- Animated Cloud compilation Icon --}}
                    <div style="position: relative; width: 68px; height: 68px; display: flex; align-items: center; justify-content: center;">
                        <div style="position: absolute; inset: 0; border: 3px solid #fde68a; border-top-color: #d97706; border-radius: 50%; animation: mam-spin 0.9s linear infinite;"></div>
                        <span style="font-size: 2rem;">⚡</span>
                    </div>

                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0;" class="dark:text-white">
                            Compiling {{ $app?->app_name ?: ($appName ?: $tenant->name) }} APK
                        </h3>
                        <p style="font-size: 0.825rem; color: #64748b; margin: 0.35rem 0 0 0;" class="dark:text-gray-400">
                            Your Android APK is compiling and signing in our cloud build runner. This usually takes around 90 seconds.
                        </p>
                    </div>

                    {{-- Live Build Steps Stepper --}}
                    <div style="width: 100%; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.15rem; margin-top: 0.5rem; text-align: left; display: flex; flex-direction: column; gap: 0.85rem; box-shadow: 0 1px 2px rgba(0,0,0,0.03);" class="dark:bg-gray-800/60 dark:border-gray-700">
                        <div style="display: flex; align-items: center; gap: 0.65rem; font-size: 0.8125rem;">
                            <span style="display: flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: #ecfdf5; color: #059669; font-weight: 800; font-size: 0.75rem;">✓</span>
                            <span style="color: #334155; font-weight: 600;" class="dark:text-gray-200">Dispatched build worker to cloud</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.65rem; font-size: 0.8125rem;">
                            <span style="display: flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: #fef3c7; color: #d97706;">
                                <span style="display: inline-block; animation: mam-spin 0.8s linear infinite; font-size: 0.7rem;">⏳</span>
                            </span>
                            <span style="color: #d97706; font-weight: 700;" class="dark:text-amber-400">Compiling native Android binary &amp; webview assets...</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.65rem; font-size: 0.8125rem; color: #94a3b8;">
                            <span style="display: flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; border: 1.5px dashed #cbd5e1; font-size: 0.65rem;">○</span>
                            <span>Sign APK with production keystore &amp; publish download link</span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.45rem; font-size: 0.75rem; color: #64748b;" class="dark:text-gray-400">
                        <span style="display: inline-block; animation: mam-spin 1.5s linear infinite;">🔄</span>
                        <span>Auto-refreshing status. Your download button will appear as soon as it's ready.</span>
                    </div>

                    {{-- Discreet Emergency Reset if build exceeds 5 minutes --}}
                    @if($app && $app->updated_at && $app->updated_at->diffInMinutes(now()) >= 5)
                        <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed #cbd5e1; width: 100%;">
                            <button wire:click="cancelBuild" 
                                    wire:confirm="This build seems stuck. Reset and allow retry?"
                                    type="button" 
                                    style="font-size: 0.75rem; color: #dc2626; background: none; border: none; text-decoration: underline; cursor: pointer;">
                                Build taking unusually long? Click here to reset
                            </button>
                        </div>
                    @endif

                </div>
            </div>

        @else

            {{-- 2. App Branding & Details Form (Shown when NOT compiling) --}}
            <div class="mam-card">
                <div style="margin-bottom: 1.25rem;">
                    <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;" class="dark:text-white">
                        App Branding &amp; Details
                    </h3>
                    <p style="font-size: 0.8125rem; color: #64748b; margin: 0.25rem 0 0 0;" class="dark:text-gray-400">
                        Customize the name and icon displayed on your customers' Android home screens.
                    </p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    
                    {{-- App Name with Read-Only Lock & Change Button --}}
                    <div x-data="{ isEditingName: @js(empty($appName) && empty($app?->app_name)) }">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                            <label style="font-size: 0.8125rem; font-weight: 700; color: #334155; margin: 0;" class="dark:text-gray-300">
                                App Name <span style="color: #dc2626;">*</span>
                            </label>
                            <span x-show="isEditingName" style="font-size: 0.725rem; font-weight: 600; color: #64748b;" class="dark:text-gray-400">
                                {{ strlen($appName) }}/30 chars
                            </span>
                        </div>

                        {{-- Locked Read-Only State --}}
                        <div x-show="!isEditingName" style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.6rem 0.85rem;" class="dark:bg-gray-800/50 dark:border-gray-700">
                            <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 0;">
                                <span style="font-size: 0.925rem; font-weight: 700; color: #0f172a; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" class="dark:text-white">
                                    {{ $appName ?: ($app?->app_name ?: $tenant->name) }}
                                </span>
                                <span style="font-size: 0.68rem; font-weight: 600; color: #64748b; background: #e2e8f0; padding: 0.1rem 0.4rem; border-radius: 4px;" class="dark:bg-gray-700 dark:text-gray-300">
                                    🔒 Locked
                                </span>
                            </div>
                            <button type="button" 
                                    x-on:click="isEditingName = true"
                                    class="mam-btn mam-btn-outline" 
                                    style="padding: 0.35rem 0.75rem; font-size: 0.75rem; flex-shrink: 0;">
                                ✏️ Change Name
                            </button>
                        </div>

                        {{-- Editable State --}}
                        <div x-show="isEditingName" style="display: none; align-items: center; gap: 0.5rem;">
                            <input type="text" 
                                   wire:model.live.debounce.150ms="appName" 
                                   placeholder="Enter your app name (e.g. {{ $tenant->name }})" 
                                   maxlength="30"
                                   class="mam-input" 
                                   style="flex: 1;" />
                            <button type="button"
                                    x-on:click="isEditingName = false"
                                    class="mam-btn mam-btn-primary"
                                    style="padding: 0.55rem 0.85rem; font-size: 0.78rem; flex-shrink: 0;">
                                ✓ Done
                            </button>
                        </div>
                        @error('appName') <span style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Launcher Icon with Read-Only Lock & Change Button --}}
                    <div x-data="{ isEditingIcon: false, uploading: false, progress: 0 }"
                         x-on:livewire-upload-start="uploading = true"
                         x-on:livewire-upload-finish="uploading = false; isEditingIcon = false"
                         x-on:livewire-upload-error="uploading = false"
                         x-on:livewire-upload-progress="progress = $event.detail.progress">
                        
                        <label style="display: block; font-size: 0.8125rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem;" class="dark:text-gray-300">
                            App Launcher Icon
                        </label>

                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem;" class="dark:bg-gray-800/40 dark:border-gray-800">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                                
                                {{-- Left: Icon Thumbnail + Info --}}
                                <div style="display: flex; align-items: center; gap: 0.85rem;">
                                    <div style="position: relative; width: 48px; height: 48px; border-radius: 12px; background: #ffffff; border: 1.5px solid #cbd5e1; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;" class="dark:bg-gray-900 dark:border-gray-700">
                                        @if($iconChoice === 'custom' && $appIcon)
                                            <img src="{{ $appIcon->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover;" alt="App Icon" />
                                        @elseif($app && $app->app_icon_path)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($app->app_icon_path) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="App Icon" />
                                        @elseif(! empty($tenant->logo_path))
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($tenant->logo_path) }}" style="width: 100%; height: 100%; object-fit: contain;" alt="Store Logo" />
                                        @else
                                            <span style="font-size: 1.5rem;">📱</span>
                                        @endif

                                        {{-- Livewire Upload Loading Spinner Overlay --}}
                                        <div wire:loading.flex wire:target="appIcon" style="display: none; position: absolute; inset: 0; background: rgba(15, 23, 42, 0.65); align-items: center; justify-content: center; z-index: 10;">
                                            <span style="display: inline-block; animation: mam-spin 1s linear infinite; font-size: 1.1rem;">⏳</span>
                                        </div>
                                    </div>

                                    <div>
                                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                                            <span style="font-size: 0.8125rem; font-weight: 700; color: #0f172a;" class="dark:text-white">
                                                @if($iconChoice === 'custom' && ($appIcon || ($app && $app->app_icon_path)))
                                                    Custom App Icon
                                                @elseif(! empty($tenant->logo_path))
                                                    Store Logo (Default)
                                                @else
                                                    Default Launcher Icon
                                                @endif
                                            </span>
                                            <span style="font-size: 0.68rem; font-weight: 600; color: #64748b; background: #e2e8f0; padding: 0.1rem 0.35rem; border-radius: 4px;" class="dark:bg-gray-700 dark:text-gray-300">
                                                🔒 Locked
                                            </span>
                                        </div>
                                        <p style="font-size: 0.725rem; color: #64748b; margin: 0.15rem 0 0 0;" class="dark:text-gray-400">
                                            Square 512x512 PNG, JPG, or WebP.
                                        </p>
                                    </div>
                                </div>

                                {{-- Right: Flex Button to Change Icon --}}
                                <div>
                                    <button type="button" 
                                            x-show="!isEditingIcon"
                                            x-on:click="isEditingIcon = true"
                                            class="mam-btn mam-btn-outline" 
                                            style="font-size: 0.78rem; padding: 0.45rem 0.85rem;">
                                        🖼️ Change Icon
                                    </button>
                                    <button type="button" 
                                            x-show="isEditingIcon"
                                            x-on:click="isEditingIcon = false"
                                            class="mam-btn mam-btn-outline" 
                                            style="display: none; font-size: 0.78rem; padding: 0.45rem 0.85rem; color: #64748b;">
                                        ✕ Done
                                    </button>
                                </div>

                            </div>

                            {{-- Expanded Edit Panel for Icon --}}
                            <div x-show="isEditingIcon" style="display: none; margin-top: 0.85rem; padding-top: 0.85rem; border-top: 1px dashed #cbd5e1;" class="dark:border-gray-700">
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                    <button type="button" 
                                            wire:loading.attr="disabled"
                                            wire:target="appIcon"
                                            onclick="document.getElementById('ab_app_icon_input').click()" 
                                            class="mam-btn mam-btn-primary" 
                                            style="font-size: 0.78rem; padding: 0.45rem 0.85rem;">
                                        <span wire:loading.remove wire:target="appIcon">📤 Upload New Icon</span>
                                        <span wire:loading.inline-flex wire:target="appIcon" style="display: none; align-items: center; gap: 0.35rem;">
                                            <span style="display: inline-block; animation: mam-spin 1s linear infinite;">⏳</span> Uploading Icon...
                                        </span>
                                    </button>

                                    @if(! empty($tenant->logo_path) && ($iconChoice === 'custom' || $appIcon))
                                        <button type="button" 
                                                wire:click="resetToStoreLogo" 
                                                x-on:click="isEditingIcon = false"
                                                class="mam-btn mam-btn-outline" 
                                                style="font-size: 0.78rem; padding: 0.45rem 0.85rem; color: #475569;">
                                            <span>🏪 Reset to Store Logo</span>
                                        </button>
                                    @endif
                                </div>

                                {{-- Live Upload Progress Bar --}}
                                <div x-show="uploading" style="display: none; margin-top: 0.65rem; width: 100%; max-width: 260px;">
                                    <div style="width: 100%; height: 5px; background: #e2e8f0; border-radius: 9999px; overflow: hidden;" class="dark:bg-gray-700">
                                        <div style="height: 100%; background: #2563eb; transition: width 0.2s;" :style="`width: ${progress}%`"></div>
                                    </div>
                                    <span style="font-size: 0.7rem; color: #2563eb; font-weight: 700; margin-top: 0.2rem; display: block;" x-text="`Uploading: ${progress}%`"></span>
                                </div>
                            </div>

                            <input type="file" 
                                   id="ab_app_icon_input" 
                                   wire:model="appIcon" 
                                   accept="image/png,image/jpeg,image/webp,.png,.jpg,.jpeg,.webp" 
                                   style="display: none;" />
                        </div>
                        @error('appIcon') <span style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Storefront Target URL & Domain Picker --}}
                    @php
                        $availableDomains = $this->getAvailableDomains();
                    @endphp
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.85rem 1rem;" class="dark:bg-gray-800/40 dark:border-gray-800">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                            <div>
                                <label style="display: block; font-size: 0.8125rem; font-weight: 700; color: #334155; margin: 0;" class="dark:text-gray-300">
                                    Storefront WebView Target
                                </label>
                                <span style="font-size: 0.75rem; color: #64748b;" class="dark:text-gray-400">
                                    The web address your customer mobile app loads upon opening.
                                </span>
                            </div>

                            @if(count($availableDomains) > 1)
                                <div>
                                    <select wire:model.live="selectedDomain" 
                                            class="mam-input" 
                                            style="padding: 0.45rem 0.75rem; font-size: 0.8125rem; width: auto; font-family: monospace; font-weight: 700;">
                                        @foreach($availableDomains as $dom)
                                            <option value="{{ $dom }}">{{ $dom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div>
                                    <code style="font-family: monospace; font-size: 0.8125rem; font-weight: 700; color: #0284c7; background: #f0f9ff; padding: 0.3rem 0.6rem; border-radius: 8px; border: 1px solid #bae6fd;" class="dark:bg-sky-950 dark:border-sky-800 dark:text-sky-300">
                                        {{ $this->getResolvedStoreUrl() }}
                                    </code>
                                </div>
                            @endif
                        </div>

                        @if(count($availableDomains) > 1)
                            <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #0284c7;" class="dark:text-sky-400">
                                Active Target URL: <strong>{{ $this->getResolvedStoreUrl() }}</strong>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- 3. Primary Compile / Action Card --}}
            <div class="mam-card" style="text-align: center;">
                @if(! $this->canCreateOrRebuildApp())
                    <button disabled type="button" class="mam-btn mam-btn-primary" style="width: 100%; max-width: 420px; margin: 0 auto;">
                        🔒 Compilation Unavailable
                    </button>
                    <span style="font-size: 0.75rem; color: #dc2626; display: block; margin-top: 0.4rem;">
                        {{ $this->getEligibilityBlockReason() }}
                    </span>
                @elseif($isReady)
                    @if($app->isPlayStorePublished())
                        <div style="max-width: 460px; margin: 0 auto; text-align: center;">
                            <span style="display: inline-block; font-size: 0.8125rem; font-weight: 700; color: #166534; background: #f0fdf4; border: 1.5px solid #bbf7d0; padding: 0.6rem 1rem; border-radius: 10px; margin-bottom: 0.75rem;" class="dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
                                🛡️ App is Live on Google Play Store
                            </span>
                            <p style="font-size: 0.75rem; color: #64748b; margin: 0 0 0.75rem 0;" class="dark:text-gray-400">
                                Automatic self-serve rebuilds are locked to avoid version code mismatches with Google Play.
                            </p>
                            <a href="https://wa.me/2348000000000?text={{ urlencode('Hello AffanHub Admin, I need an updated Google Play build for: ' . $tenant->name) }}" 
                               target="_blank"
                               class="mam-btn mam-btn-outline" 
                               style="font-size: 0.8125rem;">
                                💬 Contact Admin for Store Update
                            </a>
                        </div>
                    @else
                        <button wire:click="requestUpdate" 
                                wire:loading.attr="disabled"
                                type="button" 
                                class="mam-btn mam-btn-green"
                                style="width: 100%; max-width: 420px; margin: 0 auto; font-size: 0.95rem; padding: 0.85rem 1.5rem;">
                            <span wire:loading.remove wire:target="requestUpdate">
                                🔄 Rebuild Update (v{{ $app->version_code + 1 }}.0 - Free)
                            </span>
                            <span wire:loading.inline-flex wire:target="requestUpdate" style="display: none; align-items: center; gap: 0.35rem;">
                                <span style="display: inline-block; animation: mam-spin 1s linear infinite;">⏳</span> Dispatching Cloud Update...
                            </span>
                        </button>
                        <span style="font-size: 0.75rem; color: #64748b; display: block; margin-top: 0.45rem;" class="dark:text-gray-400">
                            Re-compiles your app with any new name or icon adjustments.
                        </span>
                    @endif
                @else
                    <button wire:click="generateFreeApp" 
                            wire:loading.attr="disabled"
                            type="button" 
                            class="mam-btn mam-btn-green"
                            style="width: 100%; max-width: 420px; margin: 0 auto; font-size: 0.95rem; padding: 0.85rem 1.5rem;">
                        <span wire:loading.remove wire:target="generateFreeApp">
                            🚀 Generate Android App (Free)
                        </span>
                        <span wire:loading.inline-flex wire:target="generateFreeApp" style="display: none; align-items: center; gap: 0.35rem;">
                            <span style="display: inline-block; animation: mam-spin 1s linear infinite;">⏳</span> Starting Cloud Build...
                        </span>
                    </button>
                    <span style="font-size: 0.75rem; color: #64748b; display: block; margin-top: 0.45rem;" class="dark:text-gray-400">
                        Compiles your custom Android APK in approximately 2 minutes. Free &amp; included.
                    </span>
                @endif
            </div>

        @endif

    </div>
</x-filament-panels::page>
