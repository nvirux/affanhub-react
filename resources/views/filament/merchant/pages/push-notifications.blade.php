<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $app = $this->mobileApp;
        $isReady = $app && $app->isReady();
        $presets = $this->destinationPresets;
        $recentBroadcasts = $this->recentPushNotifications;
    @endphp

    <style>
        .pn-container {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .pn-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
            box-sizing: border-box;
        }

        .pn-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
            width: 100%;
            box-sizing: border-box;
        }

        @media (min-width: 1024px) {
            .pn-grid {
                grid-template-columns: 1.2fr 0.8fr;
            }
        }

        .phone-mockup {
            background: #0f172a;
            border-radius: 28px;
            padding: 1rem;
            box-shadow: 0 16px 32px -8px rgba(15, 23, 42, 0.35);
            color: #ffffff;
            border: 4px solid #1e293b;
        }

        .phone-notch {
            width: 80px;
            height: 14px;
            background: #020617;
            border-radius: 9999px;
            margin: 0 auto 0.75rem auto;
        }

        .phone-notif-bubble {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 0.85rem 1rem;
            color: #0f172a;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
        }

        .preset-pill {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.75rem;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .preset-pill:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .preset-pill.active {
            border-color: #2563eb;
            background: #eff6ff;
            color: #1d4ed8;
            box-shadow: 0 1px 4px rgba(37, 99, 235, 0.15);
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

    <div class="pn-container">

        {{-- Top App Status Warning if APK not yet ready --}}
        @if(! $isReady)
            <div style="background: #fffbeb; border: 1.5px solid #fef3c7; border-radius: 14px; padding: 1.15rem 1.35rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.75rem;">📲</span>
                    <div>
                        <strong style="font-size: 0.95rem; color: #92400e; display: block;">
                            Your Android App is Not Ready Yet
                        </strong>
                        <span style="font-size: 0.8125rem; color: #b45309;">
                            Push notifications require customers to have installed your Android app. Build your app first so customers can subscribe.
                        </span>
                    </div>
                </div>
                <a href="{{ route('filament.merchant.pages.mobile-app-manager', ['tenant' => $tenant->public_id]) }}" 
                   style="background: #d97706; color: white; padding: 0.55rem 1.15rem; border-radius: 10px; font-size: 0.8125rem; font-weight: 800; text-decoration: none;">
                    Go to App Builder &rarr;
                </a>
            </div>
        @else
            <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 14px; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 36px; height: 36px; background: #16a34a; color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        🔔
                    </div>
                    <div>
                        <strong style="font-size: 0.95rem; color: #166534; display: block;">
                            {{ $app->app_name }} Cloud Push Channel is Active
                        </strong>
                        <span style="font-size: 0.8rem; color: #15803d;">
                            Push notifications will reach all customers who installed your Android App (v{{ $app->version_name }}).
                        </span>
                    </div>
                </div>
                <span style="background: #dcfce7; color: #166534; font-size: 0.75rem; font-weight: 800; padding: 0.3rem 0.65rem; border-radius: 9999px;">
                    🟢 OneSignal Connected
                </span>
            </div>
        @endif

        {{-- Main 2-Column Responsive Layout --}}
        <div class="pn-grid">

            {{-- Column 1: Notification Composer --}}
            <div class="pn-card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                    <div>
                        <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.4rem;">
                            <span>✍️</span>
                            <span>Compose Broadcast</span>
                        </h3>
                        <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem; margin-bottom: 0;">
                            Create and schedule a promotional or transactional push message for all your app customers.
                        </p>
                    </div>
                </div>

                <form wire:submit.prevent="sendBroadcast" style="display: flex; flex-direction: column; gap: 1.15rem;">

                    {{-- Title Input --}}
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <label style="font-size: 0.8125rem; font-weight: 700; color: #334155;">
                                Notification Title <span style="color: #dc2626;">*</span>
                            </label>
                            <span style="font-size: 0.7rem; color: #94a3b8;">
                                {{ strlen($pushTitle) }}/100 chars
                            </span>
                        </div>
                        <input type="text" 
                               wire:model.live.debounce.150ms="pushTitle" 
                               placeholder="e.g. Flash Promo: 10% Off All Data Bundles!" 
                               maxlength="100"
                               required
                               style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 0.7rem 0.9rem; font-size: 0.875rem; background: #ffffff; color: #0f172a; box-sizing: border-box;" />
                        @error('pushTitle') 
                            <span style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- Message Body --}}
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <label style="font-size: 0.8125rem; font-weight: 700; color: #334155;">
                                Message Content <span style="color: #dc2626;">*</span>
                            </label>
                            <span style="font-size: 0.7rem; color: #94a3b8;">
                                {{ strlen($pushMessage) }}/255 chars
                            </span>
                        </div>
                        <textarea wire:model.live.debounce.150ms="pushMessage" 
                                  rows="3" 
                                  placeholder="e.g. Stock up on MTN, Airtel and Glo data for the weekend. Valid until midnight!" 
                                  maxlength="255"
                                  required
                                  style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 0.7rem 0.9rem; font-size: 0.875rem; background: #ffffff; color: #0f172a; box-sizing: border-box; resize: vertical;"></textarea>
                        @error('pushMessage') 
                            <span style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- Destination Preset Selector --}}
                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem;">
                            Customer Tap Destination (Deep Link)
                        </label>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.65rem;">
                            @foreach($presets as $key => $preset)
                                <button type="button" 
                                        wire:click="$set('destinationPreset', '{{ $key }}')"
                                        class="preset-pill {{ $destinationPreset === $key ? 'active' : '' }}">
                                    <span>{{ $preset['icon'] }}</span>
                                    <span>{{ $preset['label'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        {{-- If custom is chosen, show input field --}}
                        @if($destinationPreset === 'custom')
                            <div style="margin-top: 0.45rem;">
                                <input type="text" 
                                       wire:model="customUrl" 
                                       placeholder="e.g. /vtu/data or https://yoursite.com/promo" 
                                       style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 0.65rem 0.85rem; font-size: 0.85rem; box-sizing: border-box;" />
                                <span style="font-size: 0.72rem; color: #64748b; margin-top: 0.25rem; display: block;">
                                    Relative paths like <code>/vtu/data</code> are automatically converted to your store's full secure domain link.
                                </span>
                            </div>
                        @else
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.55rem 0.75rem; font-size: 0.75rem; color: #64748b; display: flex; align-items: center; justify-content: space-between;">
                                <span>Target route: <strong style="color: #0f172a;">{{ $presets[$destinationPreset]['path'] ?? '/dashboard' }}</strong></span>
                                <span style="color: #94a3b8;">{{ $presets[$destinationPreset]['description'] ?? '' }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Target Audience & Submit Action --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 0.5rem; gap: 1rem; flex-wrap: wrap; padding-top: 0.75rem; border-top: 1px solid #f1f5f9;">
                        <div style="display: flex; align-items: center; gap: 0.45rem; font-size: 0.75rem; color: #64748b;">
                            <span>🎯 Target:</span>
                            <strong style="color: #0f172a;">
                                {{ $app ? "All {$app->app_name} App Installers" : "All App Customers" }}
                            </strong>
                        </div>

                        <button type="submit" 
                                wire:loading.attr="disabled"
                                style="padding: 0.75rem 1.5rem; background: #2563eb; color: #ffffff; border: none; border-radius: 10px; font-weight: 800; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
                            <span wire:loading.remove wire:target="sendBroadcast">
                                🚀 Send Broadcast Now
                            </span>
                            <span wire:loading.inline-flex wire:target="sendBroadcast" style="display: none; align-items: center; gap: 0.35rem;">
                                <span style="display: inline-block; animation: spin 1s linear infinite;">⏳</span> Sending to OneSignal...
                            </span>
                        </button>
                    </div>

                </form>
            </div>

            {{-- Column 2: Live Mobile Preview & Recent Sent Log --}}
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">

                {{-- Live Android Phone Preview --}}
                <div class="pn-card" style="background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);">
                    <h4 style="font-size: 0.85rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.85rem 0; display: flex; align-items: center; gap: 0.4rem;">
                        <span>📱</span>
                        <span>Live Phone Lockscreen Preview</span>
                    </h4>

                    <div class="phone-mockup">
                        <div class="phone-notch"></div>
                        <div style="text-align: center; margin-bottom: 0.75rem; font-size: 0.7rem; color: #94a3b8; font-weight: 600;">
                            {{ date('l, F j') }}
                        </div>

                        {{-- Simulated Push Notification Bubble --}}
                        <div class="phone-notif-bubble">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    <div style="width: 16px; height: 16px; background: #2563eb; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 9px; color: white; font-weight: 900;">
                                        🔔
                                    </div>
                                    <span style="font-size: 0.72rem; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.02em;">
                                        {{ $app->app_name ?? $tenant->name }}
                                    </span>
                                </div>
                                <span style="font-size: 0.65rem; color: #94a3b8;">Now</span>
                            </div>

                            <strong style="font-size: 0.8125rem; color: #0f172a; display: block; line-height: 1.25; margin-bottom: 0.2rem;">
                                {{ ! empty($pushTitle) ? $pushTitle : 'Your Notification Title Here' }}
                            </strong>
                            <p style="font-size: 0.75rem; color: #475569; margin: 0; line-height: 1.35;">
                                {{ ! empty($pushMessage) ? $pushMessage : 'Your broadcast message preview will appear here in real time as you type.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Recent Sent Broadcasts History --}}
                <div class="pn-card">
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.4rem;">
                        <span>📜</span>
                        <span>Recent Sent Broadcasts</span>
                    </h4>

                    @if($recentBroadcasts->isEmpty())
                        <div style="text-align: center; padding: 2rem 1rem; border: 1.5px dashed #cbd5e1; border-radius: 12px;">
                            <div style="font-size: 1.75rem; margin-bottom: 0.5rem;">📬</div>
                            <span style="font-size: 0.8125rem; font-weight: 700; color: #475569; display: block;">No Broadcasts Sent Yet</span>
                            <span style="font-size: 0.75rem; color: #94a3b8;">
                                When you dispatch notifications, their delivery logs and recipient count will appear here.
                            </span>
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 360px; overflow-y: auto;">
                            @foreach($recentBroadcasts as $item)
                                <div style="border: 1px solid #f1f5f9; background: #f8fafc; border-radius: 10px; padding: 0.85rem; display: flex; flex-direction: column; gap: 0.3rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem;">
                                        <strong style="font-size: 0.8125rem; color: #0f172a; line-height: 1.3;">
                                            {{ $item->title }}
                                        </strong>
                                        @if($item->status === 'sent')
                                            <span style="font-size: 0.68rem; font-weight: 800; background: #dcfce7; color: #15803d; padding: 0.15rem 0.45rem; border-radius: 4px; shrink-0;">
                                                Sent
                                            </span>
                                        @else
                                            <span style="font-size: 0.68rem; font-weight: 800; background: #fee2e2; color: #b91c1c; padding: 0.15rem 0.45rem; border-radius: 4px; shrink-0;" title="{{ $item->failure_reason }}">
                                                Failed
                                            </span>
                                        @endif
                                    </div>
                                    <p style="font-size: 0.75rem; color: #64748b; margin: 0; line-height: 1.35;">
                                        {{ \Illuminate\Support\Str::limit($item->message, 100) }}
                                    </p>
                                    @if($item->action_url)
                                        <div style="font-size: 0.7rem; color: #2563eb; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            🔗 {{ $item->action_url }}
                                        </div>
                                    @endif
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.25rem; font-size: 0.7rem; color: #94a3b8;">
                                        <span>🕒 {{ $item->created_at->diffForHumans() }}</span>
                                        @if($item->recipients_count > 0)
                                            <span style="font-weight: 700; color: #475569;">👥 {{ number_format($item->recipients_count) }} reached</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
</x-filament-panels::page>
