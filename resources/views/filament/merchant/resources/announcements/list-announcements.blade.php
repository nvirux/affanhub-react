<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $app = $this->mobileApp;
        $isReady = $app && $app->isReady();
        $presets = $this->destinationPresets;
        $recentBroadcasts = $this->recentPushNotifications;
        $destinationPreset = $this->destinationPreset;
    @endphp


    <style>
        .ann-tabs-bar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 0.75rem;
            flex-wrap: wrap;
        }
        :is(.dark .ann-tabs-bar) {
            border-color: #1e293b;
        }

        .ann-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.15rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 700;
            border: 1.5px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            background: #f1f5f9;
            color: #475569;
        }
        .ann-tab-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .ann-tab-btn.active {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.12);
        }
        :is(.dark .ann-tab-btn) {
            background: #1e293b;
            color: #94a3b8;
        }
        :is(.dark .ann-tab-btn:hover) {
            background: #334155;
            color: #f8fafc;
        }
        :is(.dark .ann-tab-btn.active) {
            background: #3b82f6;
            color: #ffffff;
            border-color: #3b82f6;
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
                grid-template-columns: 1.25fr 0.75fr;
            }
        }

        .pn-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
            box-sizing: border-box;
        }
        :is(.dark .pn-card) {
            background: #0f172a;
            border-color: #1e293b;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.25);
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
        :is(.dark .preset-pill) {
            background: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }
        :is(.dark .preset-pill.active) {
            background: rgba(37, 99, 235, 0.25);
            border-color: #3b82f6;
            color: #60a5fa;
        }

        .pn-input {
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
        .pn-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
        :is(.dark .pn-input) {
            background: #0b1120;
            color: #f8fafc;
            border-color: #334155;
        }
        :is(.dark .pn-input:focus) {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }
    </style>

    {{-- Tabs Header --}}
    <div class="ann-tabs-bar">
        <button type="button" 
                wire:click="setActiveTab('announcements')"
                class="ann-tab-btn {{ $activeTab === 'announcements' ? 'active' : '' }}">
            <span>📢</span>
            <span>Storefront Notices</span>
        </button>

        <button type="button" 
                wire:click="setActiveTab('push')"
                class="ann-tab-btn {{ $activeTab === 'push' ? 'active' : '' }}">
            <span>🔔</span>
            <span>App Push Notifications</span>
            @if($isReady)
                <span style="font-size: 0.68rem; padding: 0.1rem 0.45rem; border-radius: 9999px; background: #10b981; color: white;">
                    Active
                </span>
            @endif
        </button>
    </div>

    @if($activeTab === 'announcements')
        {{-- Storefront Announcements Filament Table --}}
        {{ $this->table }}
    @else
        {{-- Mobile App Push Notifications Section --}}
        <div style="display: flex; flex-direction: column; gap: 1.25rem; width: 100%;">
            
            {{-- Status Banner --}}
            @if(! $isReady)
                <div style="background: #fffbeb; border: 1.5px solid #fef3c7; border-radius: 14px; padding: 1.15rem 1.35rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;" class="dark:bg-amber-950/30 dark:border-amber-900/40">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span style="font-size: 1.75rem;">📲</span>
                        <div>
                            <strong style="font-size: 0.95rem; color: #92400e; display: block;" class="dark:text-amber-300">
                                Android App Not Built Yet
                            </strong>
                            <span style="font-size: 0.8125rem; color: #b45309;" class="dark:text-amber-400">
                                Push notifications are delivered to customers who install your Android mobile app. Build your free APK first to start broadcasting.
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('filament.merchant.pages.mobile-app-manager', ['tenant' => $tenant->public_id]) }}" 
                       style="background: #d97706; color: white; padding: 0.55rem 1.15rem; border-radius: 10px; font-size: 0.8125rem; font-weight: 800; text-decoration: none;">
                        Open App Builder &rarr;
                    </a>
                </div>
            @else
                <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 14px; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;" class="dark:bg-emerald-950/30 dark:border-emerald-900/40">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 36px; height: 36px; background: #16a34a; color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                            🔔
                        </div>
                        <div>
                            <strong style="font-size: 0.95rem; color: #166534; display: block;" class="dark:text-emerald-300">
                                {{ $app->app_name }} Cloud Push Channel is Active
                            </strong>
                            <span style="font-size: 0.8rem; color: #15803d;" class="dark:text-emerald-400">
                                Push broadcasts reach all customers who have installed your Android App (v{{ $app->version_name }}).
                            </span>
                        </div>
                    </div>
                    <span style="background: #dcfce7; color: #166534; font-size: 0.75rem; font-weight: 800; padding: 0.3rem 0.65rem; border-radius: 9999px;" class="dark:bg-emerald-900/60 dark:text-emerald-300">
                        🟢 OneSignal Connected
                    </span>
                </div>
            @endif

            {{-- 2-Column Composer + Mockup Layout --}}
            <div class="pn-grid">

                {{-- Column 1: Notification Composer --}}
                <div class="pn-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem;">
                        <div>
                            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.4rem;" class="dark:text-white">
                                <span>✍️</span>
                                <span>Compose App Broadcast</span>
                            </h3>
                            <p style="font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem; margin-bottom: 0;" class="dark:text-gray-400">
                                Send real-time promotional or service alerts directly to customers' phone notification bars.
                            </p>
                        </div>
                    </div>

                    <form wire:submit.prevent="sendBroadcast" style="display: flex; flex-direction: column; gap: 1.15rem;">

                        {{-- Title Input --}}
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                                <label style="font-size: 0.8125rem; font-weight: 700; color: #334155;" class="dark:text-gray-300">
                                    Notification Title <span style="color: #dc2626;">*</span>
                                </label>
                                <span style="font-size: 0.7rem; color: #94a3b8;">
                                    {{ strlen($this->pushTitle ?? '') }}/100 chars
                                </span>
                            </div>
                            <input type="text" 
                                   wire:model.live.debounce.150ms="pushTitle" 
                                   placeholder="e.g. Flash Promo: 10% Off All Data Bundles!" 
                                   maxlength="100"
                                   required
                                   class="pn-input" />
                            @error('pushTitle') 
                                <span style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Message Body --}}
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                                <label style="font-size: 0.8125rem; font-weight: 700; color: #334155;" class="dark:text-gray-300">
                                    Message Content <span style="color: #dc2626;">*</span>
                                </label>
                                <span style="font-size: 0.7rem; color: #94a3b8;">
                                    {{ strlen($this->pushMessage ?? '') }}/255 chars
                                </span>
                            </div>
                            <textarea wire:model.live.debounce.150ms="pushMessage" 
                                      rows="3" 
                                      placeholder="e.g. Stock up on MTN, Airtel and Glo data for the weekend. Valid until midnight!" 
                                      maxlength="255"
                                      required
                                      class="pn-input"
                                      style="resize: vertical;"></textarea>
                            @error('pushMessage') 
                                <span style="color: #dc2626; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Destination Presets --}}
                        <div>
                            <label style="font-size: 0.8125rem; font-weight: 700; color: #334155; margin-bottom: 0.45rem; display: block;" class="dark:text-gray-300">
                                When Tapped, Open Screen:
                            </label>

                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.5rem; margin-bottom: 0.75rem;">
                                @foreach($presets as $key => $preset)
                                    <button type="button" 
                                            wire:click="$set('destinationPreset', '{{ $key }}')" 
                                            class="preset-pill {{ $destinationPreset === $key ? 'active' : '' }}">
                                        <span>{{ $preset['icon'] }}</span>
                                        <span>{{ $preset['label'] }}</span>
                                    </button>
                                @endforeach
                            </div>

                            @if($destinationPreset === 'custom')
                                <div style="margin-top: 0.5rem;">
                                    <input type="text" 
                                           wire:model="customUrl" 
                                           placeholder="e.g. /vtu/airtime or https://..." 
                                           class="pn-input" />
                                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 0.25rem; display: block;">
                                        Enter an internal path (e.g. <code>/vtu/airtime</code>) or external link.
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Submit Button --}}
                        <div style="margin-top: 0.5rem;">
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    @if(! $isReady) disabled @endif
                                    style="width: 100%; background: #0f172a; color: #ffffff; padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 800; font-size: 0.925rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.45rem; box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);"
                                    class="dark:bg-blue-600 dark:hover:bg-blue-700">
                                <span wire:loading.remove wire:target="sendBroadcast">
                                    🚀 Send Push Broadcast Now
                                </span>
                                <span wire:loading.inline-flex wire:target="sendBroadcast" style="display: none; align-items: center; gap: 0.35rem;">
                                    <span>⏳</span> Dispatching to OneSignal...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Column 2: Phone Lockscreen Preview --}}
                <div class="pn-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <div style="font-size: 0.78rem; font-weight: 700; color: #64748b; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        📱 Live Phone Preview
                    </div>

                    <div class="phone-mockup" style="width: 100%; max-width: 290px;">
                        <div class="phone-notch"></div>

                        <div style="text-align: center; margin-bottom: 1.25rem;">
                            <div style="font-size: 1.85rem; font-weight: 300; line-height: 1;">
                                {{ now()->format('h:i') }}
                            </div>
                            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 0.25rem;">
                                {{ now()->format('l, F j') }}
                            </div>
                        </div>

                        {{-- Notification Bubble --}}
                        <div class="phone-notif-bubble">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                                <div style="display: flex; align-items: center; gap: 0.35rem;">
                                    <span style="font-size: 0.95rem;">📲</span>
                                    <span style="font-size: 0.72rem; font-weight: 800; color: #0f172a; text-transform: uppercase;">
                                        {{ $app->app_name ?? $tenant->name }}
                                    </span>
                                </div>
                                <span style="font-size: 0.65rem; color: #64748b;">now</span>
                            </div>

                            <div style="font-size: 0.8125rem; font-weight: 800; color: #0f172a; line-height: 1.25;">
                                {{ ! empty(trim($this->pushTitle ?? '')) ? $this->pushTitle : 'Notification Headline' }}
                            </div>

                            <div style="font-size: 0.75rem; color: #334155; margin-top: 0.25rem; line-height: 1.35;">
                                {{ ! empty(trim($this->pushMessage ?? '')) ? $this->pushMessage : 'Your broadcast message preview will appear here on your customer\'s phone.' }}
                            </div>


                            <div style="margin-top: 0.45rem; padding-top: 0.4rem; border-top: 1px solid rgba(0,0,0,0.06); font-size: 0.68rem; color: #2563eb; font-weight: 700;">
                                Opens: {{ $presets[$destinationPreset]['label'] ?? 'Storefront' }} &rarr;
                            </div>
                        </div>

                        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.68rem; color: #64748b;">
                            Swipe up to open
                        </div>
                    </div>
                </div>

            </div>

            {{-- Recent Push Broadcasts History --}}
            <div class="pn-card" style="margin-top: 0.5rem;">
                <h4 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.85rem 0;" class="dark:text-white">
                    📜 Recent Push Broadcasts
                </h4>

                @if($recentBroadcasts->isEmpty())
                    <div style="text-align: center; padding: 2rem 1rem; color: #94a3b8; font-size: 0.85rem;">
                        No push broadcasts sent yet. Compose your first message above!
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.8125rem; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 1.5px solid #e2e8f0; color: #64748b; font-size: 0.75rem;" class="dark:border-gray-800 dark:text-gray-400">
                                    <th style="padding: 0.6rem 0.5rem;">Title &amp; Message</th>
                                    <th style="padding: 0.6rem 0.5rem;">Target Screen</th>
                                    <th style="padding: 0.6rem 0.5rem;">Recipients</th>
                                    <th style="padding: 0.6rem 0.5rem;">Status</th>
                                    <th style="padding: 0.6rem 0.5rem;">Date Sent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBroadcasts as $broadcast)
                                    <tr style="border-bottom: 1px solid #f1f5f9;" class="dark:border-gray-800">
                                        <td style="padding: 0.65rem 0.5rem;">
                                            <strong style="color: #0f172a;" class="dark:text-white">{{ $broadcast->title }}</strong>
                                            <p style="margin: 0.15rem 0 0 0; color: #64748b; font-size: 0.75rem;" class="dark:text-gray-400">
                                                {{ Str::limit($broadcast->message, 80) }}
                                            </p>
                                        </td>
                                        <td style="padding: 0.65rem 0.5rem;">
                                            <code style="font-family: monospace; font-size: 0.72rem; color: #2563eb; background: #eff6ff; padding: 0.15rem 0.4rem; border-radius: 4px;" class="dark:bg-sky-950 dark:text-sky-300">
                                                {{ $broadcast->action_url ?? '/dashboard' }}
                                            </code>
                                        </td>
                                        <td style="padding: 0.65rem 0.5rem; font-weight: 700; color: #0f172a;" class="dark:text-gray-200">
                                            {{ number_format($broadcast->recipients_count ?? 0) }} devices
                                        </td>
                                        <td style="padding: 0.65rem 0.5rem;">
                                            @if($broadcast->status === 'sent')
                                                <span style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 700;">
                                                    ✓ Sent
                                                </span>
                                            @else
                                                <span style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 700;">
                                                    Failed
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 0.65rem 0.5rem; color: #64748b;" class="dark:text-gray-400">
                                            {{ $broadcast->created_at->format('M d, Y h:i A') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    @endif
</x-filament-panels::page>
