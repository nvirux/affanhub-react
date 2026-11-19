<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $storeUrl = $tenant?->getStoreUrl() ?? '/';
    @endphp

    <style>
        .layout-page-wrap {
            max-width: 860px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            font-family: inherit;
        }

        .layout-box {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            box-sizing: border-box;
        }

        .dark .layout-box {
            background-color: #111827;
            border-color: #1f2937;
        }

        .layout-header-box {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .layout-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .layout-card-option {
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            position: relative;
        }

        .dark .layout-card-option {
            background: #111827;
            border-color: #1f2937;
        }

        .layout-card-option:hover {
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .layout-card-option.is-selected {
            border-color: #ff6b00;
            background-color: rgba(255, 107, 0, 0.03);
            box-shadow: 0 0 0 1px #ff6b00;
        }

        .dark .layout-card-option.is-selected {
            border-color: #ff6b00;
            background-color: rgba(255, 107, 0, 0.08);
        }

        .preview-miniature {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.85rem;
            margin-bottom: 1rem;
            box-sizing: border-box;
            user-select: none;
            pointer-events: none;
        }

        .dark .preview-miniature {
            background: #1e293b;
            border-color: #334155;
        }

        .setting-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .dark .setting-row {
            border-color: #1f2937;
        }

        .setting-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .btn-preview {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 700;
            border-radius: 8px;
            background: #f1f5f9;
            color: #334155;
            text-decoration: none;
            transition: background 0.15s;
        }

        .dark .btn-preview {
            background: #1f2937;
            color: #e2e8f0;
        }

        .btn-preview:hover {
            background: #e2e8f0;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1.5rem;
            font-size: 0.8125rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 10px;
            background: #ff6b00;
            color: #ffffff;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(255, 107, 0, 0.25);
            transition: opacity 0.15s, transform 0.15s;
        }

        .btn-save:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .btn-save:active {
            transform: translateY(0);
        }
    </style>

    <div class="layout-page-wrap">
        {{-- Header Card --}}
        <div class="layout-box layout-header-box">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0;" class="dark:text-white">Storefront Service Layout</h3>
                <p style="font-size: 0.8125rem; color: #64748b; margin: 0.25rem 0 0 0;" class="dark:text-gray-400">Choose how services are displayed on your customer dashboard and mobile home screen.</p>
            </div>
            <a href="{{ $storeUrl }}" target="_blank" class="btn-preview">
                <span>Preview Storefront</span>
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>

        <form wire:submit.prevent="save" style="display: flex; flex-direction: column; gap: 1.5rem;">
            {{-- Section 1: Layout Selection --}}
            <div class="layout-box">
                <div style="margin-bottom: 0.5rem;">
                    <h4 style="font-size: 0.875rem; font-weight: 800; color: #0f172a; margin: 0;" class="dark:text-white">Dashboard Grid Style</h4>
                    <p style="font-size: 0.78125rem; color: #64748b; margin: 0.25rem 0 0 0;" class="dark:text-gray-400">Select between the standard 4-column compact grid or the larger 2-card per row layout.</p>
                </div>

                <div class="layout-card-grid">
                    {{-- Option 1: 4 Cards Per Row --}}
                    <label class="layout-card-option {{ $serviceLayout === 'compact_4' ? 'is-selected' : '' }}">
                        <input type="radio" wire:model.live="serviceLayout" value="compact_4" style="display: none;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span style="font-size: 0.6875rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 6px; background: #f1f5f9; color: #475569;" class="dark:bg-gray-800 dark:text-gray-300">Standard Fintech</span>
                            @if($serviceLayout === 'compact_4')
                                <div style="width: 18px; height: 18px; border-radius: 50%; background: #ff6b00; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800;">✓</div>
                            @endif
                        </div>

                        {{-- Miniature 4-column diagram --}}
                        <div class="preview-miniature">
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px;">
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 2px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 4px;" class="dark:bg-gray-900 dark:border-gray-700">
                                    <div style="width: 16px; height: 16px; border-radius: 4px; background: rgba(255,107,0,0.2);"></div>
                                    <div style="width: 22px; height: 4px; border-radius: 2px; background: #cbd5e1;"></div>
                                </div>
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 2px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 4px;" class="dark:bg-gray-900 dark:border-gray-700">
                                    <div style="width: 16px; height: 16px; border-radius: 4px; background: rgba(16,185,129,0.2);"></div>
                                    <div style="width: 22px; height: 4px; border-radius: 2px; background: #cbd5e1;"></div>
                                </div>
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 2px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 4px;" class="dark:bg-gray-900 dark:border-gray-700">
                                    <div style="width: 16px; height: 16px; border-radius: 4px; background: rgba(245,158,11,0.2);"></div>
                                    <div style="width: 22px; height: 4px; border-radius: 2px; background: #cbd5e1;"></div>
                                </div>
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 2px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 4px;" class="dark:bg-gray-900 dark:border-gray-700">
                                    <div style="width: 16px; height: 16px; border-radius: 4px; background: rgba(147,51,234,0.2);"></div>
                                    <div style="width: 22px; height: 4px; border-radius: 2px; background: #cbd5e1;"></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.875rem; font-weight: 800; color: #0f172a;" class="dark:text-white">4 Cards Per Row (Compact Grid)</div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; line-height: 1.4;" class="dark:text-gray-400">High-density grid. Fits up to 8 services cleanly on mobile and desktop dashboards with minimal scrolling.</div>
                        </div>
                    </label>

                    {{-- Option 2: 2 Cards Per Row --}}
                    <label class="layout-card-option {{ $serviceLayout === 'cards_2' ? 'is-selected' : '' }}">
                        <input type="radio" wire:model.live="serviceLayout" value="cards_2" style="display: none;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span style="font-size: 0.6875rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 6px; background: rgba(255,107,0,0.1); color: #ff6b00;">Featured & Big</span>
                            @if($serviceLayout === 'cards_2')
                                <div style="width: 18px; height: 18px; border-radius: 50%; background: #ff6b00; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800;">✓</div>
                            @endif
                        </div>

                        {{-- Miniature 2-column diagram --}}
                        <div class="preview-miniature">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px;">
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 8px; display: flex; align-items: center; gap: 6px;" class="dark:bg-gray-900 dark:border-gray-700">
                                    <div style="width: 18px; height: 18px; border-radius: 4px; background: rgba(255,107,0,0.2); shrink-0;"></div>
                                    <div style="display: flex; flex-direction: column; gap: 3px; flex: 1;">
                                        <div style="width: 28px; height: 4px; border-radius: 2px; background: #475569;"></div>
                                        <div style="width: 36px; height: 3px; border-radius: 2px; background: #cbd5e1;"></div>
                                    </div>
                                </div>
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px 8px; display: flex; align-items: center; gap: 6px;" class="dark:bg-gray-900 dark:border-gray-700">
                                    <div style="width: 18px; height: 18px; border-radius: 4px; background: rgba(16,185,129,0.2); shrink-0;"></div>
                                    <div style="display: flex; flex-direction: column; gap: 3px; flex: 1;">
                                        <div style="width: 28px; height: 4px; border-radius: 2px; background: #475569;"></div>
                                        <div style="width: 36px; height: 3px; border-radius: 2px; background: #cbd5e1;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 0.875rem; font-weight: 800; color: #0f172a;" class="dark:text-white">2 Cards Per Row (Big Cards)</div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; line-height: 1.4;" class="dark:text-gray-400">Larger cards featuring prominent service icons, bold titles, and subheadings. Ideal for CAC and Identity services.</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Section 2: Display Preferences --}}
            <div class="layout-box">
                <div style="margin-bottom: 0.5rem;">
                    <h4 style="font-size: 0.875rem; font-weight: 800; color: #0f172a; margin: 0;" class="dark:text-white">Display Preferences</h4>
                    <p style="font-size: 0.78125rem; color: #64748b; margin: 0.25rem 0 0 0;" class="dark:text-gray-400">Control helper descriptions and mobile shortcut density.</p>
                </div>

                {{-- Toggle: Service Descriptions --}}
                <div class="setting-row">
                    <div>
                        <div style="font-size: 0.875rem; font-weight: 700; color: #1e293b;" class="dark:text-white">Show Service Descriptions</div>
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.15rem;" class="dark:text-gray-400">Display short helper subtitle underneath service titles (e.g. "Top up airtime", "Verify & print slips").</div>
                    </div>
                    <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer; shrink-0;">
                        <input type="checkbox" wire:model.live="showServiceDescriptions" style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: {{ $showServiceDescriptions ? '#ff6b00' : '#cbd5e1' }}; border-radius: 24px; transition: .2s;">
                            <span style="position: absolute; content: ''; height: 18px; width: 18px; left: {{ $showServiceDescriptions ? '22px' : '3px' }}; bottom: 3px; background-color: white; border-radius: 50%; transition: .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                        </span>
                    </label>
                </div>

                {{-- Mobile Shortcuts Dropdown --}}
                <div class="setting-row">
                    <div>
                        <div style="font-size: 0.875rem; font-weight: 700; color: #1e293b;" class="dark:text-white">Mobile Home Screen Shortcuts</div>
                        <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.15rem;" class="dark:text-gray-400">Number of prioritized services to show before the "More" button.</div>
                    </div>
                    <div style="min-width: 200px;">
                        <select wire:model.live="mobileQuickActionsCount" style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.8125rem; font-weight: 600; background: #ffffff;" class="dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                            <option value="7">Top 7 Services + More Button</option>
                            <option value="11">Top 11 Services + More Button</option>
                            <option value="999">Show All Enabled Services</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-save">
                    <span>Save Layout Settings</span>
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
