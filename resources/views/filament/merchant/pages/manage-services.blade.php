<x-filament-panels::page>
    @php
        $enabledCount = collect($serviceSettings)->filter(fn($i) => $i['has_access'] && $i['is_enabled'])->count();
        $lockedCount = collect($serviceSettings)->filter(fn($i) => !$i['has_access'])->count();
        $vtuCount = collect($serviceSettings)->filter(fn($i) => $i['category'] === 'vtu')->count();
        $identityCount = collect($serviceSettings)->filter(fn($i) => $i['category'] === 'identity')->count();

        $filteredServices = collect($serviceSettings)->filter(function($item) use ($categoryFilter, $searchQuery) {
            if ($categoryFilter !== 'all' && $item['category'] !== $categoryFilter) {
                return false;
            }
            if (!empty($searchQuery) && strpos(strtolower($item['name']), strtolower($searchQuery)) === false) {
                return false;
            }
            return true;
        })->sortBy([
            fn($a, $b) => ($b['has_access'] ? 1 : 0) <=> ($a['has_access'] ? 1 : 0),
            ['sort_order', 'asc'],
        ]);
    @endphp

    <div style="font-family: inherit; display: flex; flex-direction: column; gap: 2rem;">

        {{-- Top Summary Section --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">

            {{-- Storefront Status Card --}}
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                <div style="position: absolute; right: -20px; top: -20px; width: 120px; height: 120px; border-radius: 50%; background-color: rgba(245, 158, 11, 0.08); filter: blur(20px);"></div>

                <div>
                    <h3 style="font-size: 0.75rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Storefront Layout Status</h3>
                    
                    <div style="margin-top: 1rem; display: flex; align-items: baseline; gap: 0.5rem;">
                        <span style="font-size: 2rem; font-weight: 800; color: #111111; letter-spacing: -0.02em;">
                            {{ $enabledCount }} Active
                        </span>
                        <span style="font-size: 0.875rem; font-weight: 500; color: #666666;">
                            / {{ count($serviceSettings) }} Total Services
                        </span>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.875rem; border-top: 1px solid #f3f4f6; pt-3;">
                    <span style="color: #666666;">Storefront Quick Grid</span>
                    <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; color: #d97706; background-color: #fef3c7;">
                        Top 7 Displayed
                    </span>
                </div>
            </div>

            {{-- Service Capabilities Card --}}
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; justify-content: space-between;">
                <h3 style="font-size: 0.75rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Plan Feature Availability</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div style="padding: 1rem; border-radius: 12px; background-color: #f9fafb; border: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">VTU Utilities</span>
                            <span style="font-size: 0.6875rem; color: #9ca3af;">Airtime, Data, Bills</span>
                        </div>
                        <span style="padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background-color: #d1fae5; color: #065f46;">
                            {{ $vtuCount }} Available
                        </span>
                    </div>

                    <div style="padding: 1rem; border-radius: 12px; background-color: #f9fafb; border: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Identity Services</span>
                            <span style="font-size: 0.6875rem; color: #9ca3af;">NIN, BVN, IPE</span>
                        </div>
                        <span style="padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; {{ $lockedCount > 0 ? 'background-color: #fef3c7; color: #92400e;' : 'background-color: #d1fae5; color: #065f46;' }}">
                            {{ $identityCount - $lockedCount }} / {{ $identityCount }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter & Search Header Controls --}}
        <div style="display: flex; flex-wrap: wrap; items-center; justify-content: space-between; gap: 1rem; background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1rem 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);">
            
            {{-- Category Filter Pills (Same as Billing Monthly/Yearly toggle style) --}}
            <div style="display: inline-flex; background-color: #f3f4f6; padding: 0.25rem; border-radius: 12px; align-items: center;">
                <button 
                    wire:click="setCategoryFilter('all')" 
                    style="padding: 0.5rem 1.25rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.2s; {{ $categoryFilter === 'all' ? 'background-color: white; color: #d97706; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'background-color: transparent; color: #4b5563;' }}">
                    All Services ({{ count($serviceSettings) }})
                </button>
                <button 
                    wire:click="setCategoryFilter('vtu')" 
                    style="padding: 0.5rem 1.25rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.2s; {{ $categoryFilter === 'vtu' ? 'background-color: white; color: #d97706; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'background-color: transparent; color: #4b5563;' }}">
                    VTU Utilities ({{ $vtuCount }})
                </button>
                <button 
                    wire:click="setCategoryFilter('identity')" 
                    style="padding: 0.5rem 1.25rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.2s; {{ $categoryFilter === 'identity' ? 'background-color: white; color: #7c3aed; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'background-color: transparent; color: #4b5563;' }}">
                    Identity Services ({{ $identityCount }})
                </button>
            </div>

            {{-- Search Box --}}
            <div style="position: relative; min-width: 240px;">
                <input 
                    type="text" 
                    wire:model.live="searchQuery" 
                    placeholder="Search services..." 
                    style="width: 100%; padding: 0.5rem 1rem; border-radius: 10px; border: 1px solid #e5e7eb; font-size: 0.875rem; outline: none; background-color: #f9fafb;"
                />
            </div>
        </div>

        {{-- Services Grid Cards (Matching Billing Cards Grid) --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
            @forelse($filteredServices as $serviceId => $item)
                <div style="background-color: white; border: 1px solid {{ $item['is_enabled'] && $item['has_access'] ? '#f59e0b' : 'rgba(0, 0, 0, 0.08)' }}; border-radius: 16px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                    
                    <div>
                        {{-- Top Header Row --}}
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; margin-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background-color: {{ $item['category'] === 'identity' ? '#f5f3ff' : '#fffbeb' }}; color: {{ $item['category'] === 'identity' ? '#7c3aed' : '#d97706' }}; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 700; flex-shrink: 0;">
                                    @if($item['key'] === 'airtime') 📱
                                    @elseif($item['key'] === 'data') 📶
                                    @elseif($item['key'] === 'cable') 📺
                                    @elseif($item['key'] === 'electricity') ⚡
                                    @elseif($item['key'] === 'exam_pins') 🎓
                                    @elseif($item['key'] === 'airtime_cash') 🔄
                                    @elseif($item['key'] === 'bulk_sms') 💬
                                    @elseif($item['key'] === 'nin_verification') 🪪
                                    @elseif($item['key'] === 'bvn_verification') 👤
                                    @elseif($item['key'] === 'nin_retrieval') 🔍
                                    @elseif($item['key'] === 'nin_modification') 📝
                                    @elseif($item['key'] === 'ipe_clearance') 📄
                                    @else ⚡
                                    @endif
                                </div>
                                <div>
                                    <h4 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0; line-height: 1.2;">
                                        {{ $item['name'] }}
                                    </h4>
                                    <span style="font-size: 0.6875rem; font-weight: 700; uppercase; tracking: 0.05em; color: {{ $item['category'] === 'identity' ? '#7c3aed' : '#d97706' }};">
                                        {{ strtoupper($item['category']) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Active/Locked Badge --}}
                            @if(!$item['has_access'])
                                <span style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #fef2f2; color: #991b1b; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    🔒 {{ $item['required_plan'] }}
                                </span>
                            @elseif($item['is_enabled'])
                                <span style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #ecfdf5; color: #065f46; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #10b981;"></span> Active
                                </span>
                            @else
                                <span style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #f3f4f6; color: #4b5563; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    Hidden
                                </span>
                            @endif
                        </div>

                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0; margin-bottom: 1.5rem; min-height: 38px; line-height: 1.4;">
                            {{ $item['description'] }}
                        </p>
                    </div>

                    {{-- Bottom Action Row --}}
                    <div style="border-top: 1px solid #f3f4f6; padding-top: 1.25rem; margin-top: 0.5rem; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                        
                        {{-- Sort Order Control --}}
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; color: #4b5563; font-weight: 600;">
                            <span>Order:</span>
                            <input 
                                type="number" 
                                value="{{ $item['sort_order'] }}"
                                wire:change="updateSortOrder({{ $item['id'] }}, $event.target.value)"
                                style="width: 54px; text-align: center; font-weight: 700; padding: 0.375rem 0.25rem; border-radius: 8px; border: 1px solid #d1d5db; background-color: #f9fafb; font-size: 0.875rem; outline: none;"
                                {{ !$item['has_access'] ? 'disabled' : '' }}
                            />
                        </div>

                        {{-- Action Button (Matching Billing Subscribe Button) --}}
                        @if(!$item['has_access'])
                            <button disabled style="border: none; background-color: #f3f4f6; color: #9ca3af; font-weight: 700; padding: 0.625rem 1rem; border-radius: 10px; font-size: 0.8125rem; cursor: not-allowed;">
                                Locked
                            </button>
                        @elseif($item['is_enabled'])
                            <button wire:click="toggleService({{ $item['id'] }})" style="border: none; background-color: #ef4444; color: white; font-weight: 700; padding: 0.625rem 1rem; border-radius: 10px; font-size: 0.8125rem; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.15);">
                                Hide Service
                            </button>
                        @else
                            <button wire:click="toggleService({{ $item['id'] }})" style="border: none; background-color: #f59e0b; color: white; font-weight: 700; padding: 0.625rem 1rem; border-radius: 10px; font-size: 0.8125rem; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.15);">
                                Enable Service
                            </button>
                        @endif

                    </div>

                </div>
            @empty
                <div style="grid-column: 1 / -1; background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 3rem; text-align: center; color: #6b7280; font-weight: 600;">
                    No services found matching your criteria.
                </div>
            @endforelse
        </div>

    </div>
</x-filament-panels::page>
