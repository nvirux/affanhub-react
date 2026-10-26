<x-filament-panels::page>
    @php
        $currentPlan = $this->currentPlan;
        $isPro = $this->isProOrHigher;
    @endphp

    <style>
        .rates-page-wrap {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            font-family: inherit;
        }

        /* STATUS / UPGRADE BANNER */
        .rates-header-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .dark .rates-header-card {
            background-color: #111827;
            border-color: #1f2937;
        }

        @media (min-width: 640px) {
            .rates-header-card {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 1.5rem;
            }
        }

        .rates-tag-pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #f3f4f6;
            color: #374151;
        }

        .dark .rates-tag-pill {
            background-color: #1f2937;
            color: #d1d5db;
        }

        .rates-tag-pill-pro {
            background-color: #ecfdf5;
            color: #065f46;
        }

        .dark .rates-tag-pill-pro {
            background-color: #064e3b;
            color: #a7f3d0;
        }

        .rates-primary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 8px;
            background-color: #111827;
            color: #ffffff;
            text-decoration: none;
            white-space: nowrap;
            transition: background-color 0.15s ease;
        }

        .rates-primary-btn:hover {
            background-color: #1f2937;
            color: #ffffff;
        }

        .dark .rates-primary-btn {
            background-color: #f9fafb;
            color: #111827;
        }

        .dark .rates-primary-btn:hover {
            background-color: #ffffff;
            color: #111827;
        }

        .rates-secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #374151;
            text-decoration: none;
            white-space: nowrap;
        }

        .dark .rates-secondary-btn {
            background-color: #1f2937;
            border-color: #374151;
            color: #d1d5db;
        }

        /* TABS (WRAPS ON MOBILE TO PREVENT CUTOFF/SCROLLBAR) */
        .rates-nav-tabs {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px;
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            flex-wrap: wrap;
            width: 100%;
        }

        @media (min-width: 640px) {
            .rates-nav-tabs {
                display: inline-flex;
                width: fit-content;
            }
        }

        .dark .rates-nav-tabs {
            background-color: #1f2937;
            border-color: #374151;
        }

        .rates-tab-item {
            padding: 6px 12px;
            font-size: 0.8125rem;
            font-weight: 500;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            background-color: transparent;
            color: #6b7280;
            transition: all 0.15s ease;
            white-space: nowrap;
            text-align: center;
            flex: 1 1 auto;
        }

        @media (min-width: 640px) {
            .rates-tab-item {
                flex: 0 0 auto;
                padding: 6px 14px;
            }
        }

        .rates-tab-item:hover {
            color: #111827;
        }

        .dark .rates-tab-item:hover {
            color: #ffffff;
        }

        .rates-tab-item.active {
            background-color: #ffffff;
            color: #111827;
            font-weight: 600;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        }

        .dark .rates-tab-item.active {
            background-color: #111827;
            color: #ffffff;
        }

        /* MAIN CONTENT CARD */
        .rates-main-panel {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .dark .rates-main-panel {
            background-color: #111827;
            border-color: #1f2937;
        }

        /* FILTER & SEARCH BAR */
        .rates-controls-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            background-color: #ffffff;
        }

        .dark .rates-controls-row {
            background-color: #111827;
            border-bottom-color: #1f2937;
        }

        .rates-select-control {
            padding: 6px 12px;
            font-size: 0.8125rem;
            font-weight: 500;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: #ffffff;
            color: #111827;
            outline: none;
            min-width: 150px;
        }

        .dark .rates-select-control {
            background-color: #1f2937;
            border-color: #374151;
            color: #f3f4f6;
        }

        .rates-search-control {
            padding: 6px 12px;
            font-size: 0.8125rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: #ffffff;
            color: #111827;
            outline: none;
            width: 100%;
            max-width: 260px;
        }

        .dark .rates-search-control {
            background-color: #1f2937;
            border-color: #374151;
            color: #f3f4f6;
        }

        /* DESKTOP TABLE */
        .rates-table-desktop {
            display: none;
            width: 100%;
            overflow-x: auto;
        }

        @media (min-width: 768px) {
            .rates-table-desktop {
                display: block;
            }
        }

        .rates-grid-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.8125rem;
        }

        .rates-grid-table th {
            padding: 10px 16px;
            background-color: #f9fafb;
            color: #6b7280;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e5e7eb;
        }

        .dark .rates-grid-table th {
            background-color: #182234;
            color: #9ca3af;
            border-bottom-color: #1f2937;
        }

        .rates-grid-table td {
            padding: 10px 16px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: middle;
        }

        .dark .rates-grid-table td {
            border-bottom-color: #1f2937;
            color: #d1d5db;
        }

        /* ZEBRA STRIPING FOR TABLE */
        .rates-grid-table tbody tr:nth-child(even) td {
            background-color: #f9fafb;
        }

        .dark .rates-grid-table tbody tr:nth-child(even) td {
            background-color: rgba(31, 41, 55, 0.35);
        }

        .rates-grid-table tr:hover td {
            background-color: #f3f4f6;
        }

        .dark .rates-grid-table tr:hover td {
            background-color: rgba(31, 41, 55, 0.6);
        }

        /* MOBILE CARDS (ZEBRA STRIPED) */
        .rates-list-mobile {
            display: block;
        }

        @media (min-width: 768px) {
            .rates-list-mobile {
                display: none;
            }
        }

        .rates-card-row {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            gap: 6px;
            background-color: #ffffff;
        }

        .dark .rates-card-row {
            background-color: #111827;
            border-bottom-color: #1f2937;
        }

        /* ZEBRA STRIPING FOR MOBILE CARDS */
        .rates-card-row:nth-child(even) {
            background-color: #f9fafb;
        }

        .dark .rates-card-row:nth-child(even) {
            background-color: rgba(31, 41, 55, 0.35);
        }

        .rates-card-row:last-child {
            border-bottom: none;
        }

        /* BADGES */
        .rates-chip {
            display: inline-flex;
            align-items: center;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 600;
            background-color: #f3f4f6;
            color: #374151;
        }

        .dark .rates-chip {
            background-color: #1f2937;
            color: #d1d5db;
        }

        .rates-chip-sub {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            color: #4b5563;
        }

        .dark .rates-chip-sub {
            background-color: #111827;
            border-color: #374151;
            color: #9ca3af;
        }

        .rates-savings-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 600;
            background-color: #ecfdf5;
            color: #065f46;
        }

        .dark .rates-savings-badge {
            background-color: #064e3b;
            color: #a7f3d0;
        }
    </style>

    <div class="rates-page-wrap">
        {{-- SUBSCRIPTION / TIER HEADER --}}
        @if(!$isPro)
            <div class="rates-header-card">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span class="rates-tag-pill">Current: {{ $currentPlan?->name ?? 'Starter' }}</span>
                        <span style="font-size: 0.78rem; color: #6b7280;">Standard Wholesale Rates</span>
                    </div>
                    <div style="font-size: 0.9375rem; font-weight: 600; color: #111827;" class="dark:text-white">
                        Discounted wholesale rates are available on the Pro plan
                    </div>
                    <div style="font-size: 0.8125rem; color: #6b7280; margin-top: 2px;">
                        Upgrading to Pro reduces your wholesale purchase price across data bundles and increases airtime margins.
                    </div>
                </div>
                <div style="flex-shrink: 0;">
                    <a
                        href="{{ route('filament.merchant.pages.billing', ['tenant' => \Filament\Facades\Filament::getTenant()->public_id]) }}"
                        class="rates-primary-btn"
                    >
                        Upgrade to Pro Plan
                    </a>
                </div>
            </div>
        @else
            <div class="rates-header-card">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span class="rates-tag-pill rates-tag-pill-pro">Active: {{ $currentPlan?->name ?? 'Pro' }}</span>
                        <span style="font-size: 0.78rem; color: #059669;">Lowest Wholesale Rates Active</span>
                    </div>
                    <div style="font-size: 0.9375rem; font-weight: 600; color: #111827;" class="dark:text-white">
                        Your account is on our VIP wholesale pricing tier
                    </div>
                    <div style="font-size: 0.8125rem; color: #6b7280; margin-top: 2px;">
                        Every transaction is deducted at wholesale discount rates to maximize your profit margin.
                    </div>
                </div>
                <div style="flex-shrink: 0;">
                    <a
                        href="{{ \App\Filament\Merchant\Resources\StoreDataPlanResource::getUrl('index') }}"
                        class="rates-secondary-btn"
                    >
                        Manage Store Selling Prices
                    </a>
                </div>
            </div>
        @endif

        {{-- CLEAN SEGMENTED NAVIGATION TABS --}}
        <div>
            <div class="rates-nav-tabs">
                <button
                    type="button"
                    wire:click="selectTab('data')"
                    class="rates-tab-item {{ $activeTab === 'data' ? 'active' : '' }}"
                >
                    Data Bundles
                </button>
                <button
                    type="button"
                    wire:click="selectTab('airtime')"
                    class="rates-tab-item {{ $activeTab === 'airtime' ? 'active' : '' }}"
                >
                    Airtime Discounts
                </button>
                <button
                    type="button"
                    wire:click="selectTab('identity')"
                    class="rates-tab-item {{ ($activeTab === 'identity' || $activeTab === 'utilities') ? 'active' : '' }}"
                >
                    Identity Services
                </button>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- TAB 1: DATA BUNDLES --}}
        {{-- ======================================================== --}}
        @if($activeTab === 'data')
            <div class="rates-main-panel">
                {{-- FLEX FILTER & SEARCH BAR --}}
                <div class="rates-controls-row">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label for="networkFilterSelect" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">
                            Network:
                        </label>
                        <select
                            id="networkFilterSelect"
                            wire:model.live="selectedNetwork"
                            class="rates-select-control"
                        >
                            <option value="all">All Networks</option>
                            @foreach($this->networks as $net)
                                <option value="{{ $net->id }}">{{ $net->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="width: 100%; max-width: 240px;">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search plans (e.g. 1GB, SME)..."
                            class="rates-search-control"
                        />
                    </div>
                </div>

                {{-- DESKTOP TABLE VIEW (2 DYNAMIC COLUMNS) --}}
                <div class="rates-table-desktop">
                    <table class="rates-grid-table">
                        <thead>
                            <tr>
                                <th>Network & Type</th>
                                <th>Plan Name</th>
                                <th>Validity</th>
                                @if(!$isPro)
                                    <th style="background-color: #f3f4f6;" class="dark:bg-gray-800">
                                        Starter Rate <span style="font-size: 0.68rem; font-weight: 500; text-transform: none; color: #6b7280;">(Your Rate)</span>
                                    </th>
                                    <th>
                                        Pro VIP Rate
                                    </th>
                                @else
                                    <th>
                                        Starter Rate
                                    </th>
                                    <th style="background-color: #f3f4f6;" class="dark:bg-gray-800">
                                        Pro VIP Rate <span style="font-size: 0.68rem; font-weight: 500; text-transform: none; color: #059669;">(Your Rate)</span>
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->dataPlansList as $plan)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <span class="rates-chip">{{ $plan['network'] }}</span>
                                            <span class="rates-chip rates-chip-sub">{{ $plan['data_type'] }}</span>
                                        </div>
                                    </td>
                                    <td style="font-weight: 600; color: #111827;" class="dark:text-white">
                                        {{ $plan['name'] }}
                                    </td>
                                    <td style="color: #6b7280; font-size: 0.78rem;">
                                        {{ $plan['validity'] }}
                                    </td>
                                    @if(!$isPro)
                                        <td style="font-family: monospace; font-size: 0.8125rem; font-weight: 700; background-color: #f9fafb;" class="dark:bg-gray-800/60">
                                            ₦{{ number_format($plan['starter_rate'], 2) }}
                                        </td>
                                        <td style="font-family: monospace; font-size: 0.8125rem; font-weight: 600;">
                                            ₦{{ number_format($plan['pro_rate'], 2) }}
                                            @if($plan['savings'] > 0)
                                                <span class="rates-savings-badge" style="margin-left: 6px;">
                                                    Save ₦{{ number_format($plan['savings'], 2) }}
                                                </span>
                                            @endif
                                        </td>
                                    @else
                                        <td style="font-family: monospace; font-size: 0.8125rem; color: #6b7280;">
                                            ₦{{ number_format($plan['starter_rate'], 2) }}
                                        </td>
                                        <td style="font-family: monospace; font-size: 0.8125rem; font-weight: 700; background-color: #f9fafb; color: #065f46;" class="dark:bg-gray-800/60 dark:text-emerald-300">
                                            ₦{{ number_format($plan['pro_rate'], 2) }}
                                            <span class="rates-savings-badge" style="margin-left: 6px;">
                                                VIP Active
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 2.5rem 1rem; color: #6b7280;">
                                        No data plans found matching your search.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE LIST VIEW (FITS 100% WIDTH, ZERO HORIZONTAL SCROLL) --}}
                <div class="rates-list-mobile">
                    @forelse($this->dataPlansList as $plan)
                        <div class="rates-card-row">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                    <span class="rates-chip">{{ $plan['network'] }}</span>
                                    <span style="font-weight: 600; font-size: 0.875rem; color: #111827;" class="dark:text-white">
                                        {{ $plan['name'] }}
                                    </span>
                                    <span class="rates-chip rates-chip-sub">{{ $plan['data_type'] }}</span>
                                </div>
                                <span style="font-size: 0.75rem; color: #9ca3af; white-space: nowrap;">
                                    {{ $plan['validity'] }}
                                </span>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; padding-top: 4px; border-top: 1px dashed #f3f4f6;" class="dark:border-gray-800">
                                <div>
                                    <span style="color: #6b7280;">Your Cost: </span>
                                    <span style="font-family: monospace; font-weight: 700; color: #111827;" class="dark:text-white">
                                        ₦{{ number_format($plan['active_rate'], 2) }}
                                    </span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    @if(!$isPro)
                                        <span style="color: #6b7280;">Pro: </span>
                                        <span style="font-family: monospace; font-weight: 600;">
                                            ₦{{ number_format($plan['pro_rate'], 2) }}
                                        </span>
                                        @if($plan['savings'] > 0)
                                            <span class="rates-savings-badge">
                                                -₦{{ number_format($plan['savings']) }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="rates-savings-badge">
                                            VIP Active (₦{{ number_format($plan['pro_rate'], 2) }})
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 2rem 1rem; color: #6b7280; font-size: 0.8125rem;">
                            No data plans found matching your search.
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- ======================================================== --}}
        {{-- TAB 2: AIRTIME DISCOUNTS --}}
        {{-- ======================================================== --}}
        @if($activeTab === 'airtime')
            <div class="rates-main-panel">
                <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb;" class="dark:border-gray-800">
                    <div style="font-size: 0.9375rem; font-weight: 600; color: #111827;" class="dark:text-white">
                        Airtime Wholesale Purchase Discounts
                    </div>
                    <div style="font-size: 0.78rem; color: #6b7280; margin-top: 2px;">
                        Purchase airtime at a wholesale discount below face value and retain the margin.
                    </div>
                </div>

                {{-- DESKTOP VIEW (2 DYNAMIC COLUMNS) --}}
                <div class="rates-table-desktop">
                    <table class="rates-grid-table">
                        <thead>
                            <tr>
                                <th>Network</th>
                                @if(!$isPro)
                                    <th style="background-color: #f3f4f6;" class="dark:bg-gray-800">
                                        Starter Discount <span style="font-size: 0.68rem; font-weight: 500; text-transform: none; color: #6b7280;">(Your Rate)</span>
                                    </th>
                                    <th>
                                        Pro VIP Discount
                                    </th>
                                @else
                                    <th>
                                        Starter Discount
                                    </th>
                                    <th style="background-color: #f3f4f6;" class="dark:bg-gray-800">
                                        Pro VIP Discount <span style="font-size: 0.68rem; font-weight: 500; text-transform: none; color: #059669;">(Your Rate)</span>
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->airtimeDiscountsList as $airtime)
                                <tr>
                                    <td style="font-weight: 600; color: #111827;" class="dark:text-white">
                                        {{ $airtime['network'] }}
                                    </td>
                                    @if(!$isPro)
                                        <td style="font-family: monospace; font-size: 0.8125rem; font-weight: 700; background-color: #f9fafb;" class="dark:bg-gray-800/60">
                                            {{ number_format($airtime['starter_discount'], 2) }}%
                                        </td>
                                        <td style="font-family: monospace; font-size: 0.8125rem; font-weight: 600;">
                                            {{ number_format($airtime['pro_discount'], 2) }}%
                                            @if($airtime['extra_discount'] > 0)
                                                <span class="rates-savings-badge" style="margin-left: 6px;">
                                                    +{{ number_format($airtime['extra_discount'], 2) }}% Extra Margin
                                                </span>
                                            @endif
                                        </td>
                                    @else
                                        <td style="font-family: monospace; font-size: 0.8125rem; color: #6b7280;">
                                            {{ number_format($airtime['starter_discount'], 2) }}%
                                        </td>
                                        <td style="font-family: monospace; font-size: 0.8125rem; font-weight: 700; background-color: #f9fafb; color: #065f46;" class="dark:bg-gray-800/60 dark:text-emerald-300">
                                            {{ number_format($airtime['pro_discount'], 2) }}%
                                            <span class="rates-savings-badge" style="margin-left: 6px;">
                                                VIP Active
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW --}}
                <div class="rates-list-mobile">
                    @foreach($this->airtimeDiscountsList as $airtime)
                        <div class="rates-card-row">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-weight: 600; font-size: 0.875rem; color: #111827;" class="dark:text-white">
                                    {{ $airtime['network'] }}
                                </span>
                                <span style="font-family: monospace; font-size: 0.8125rem; font-weight: 700; color: #111827;" class="dark:text-white">
                                    {{ number_format($airtime['active_discount'], 2) }}% Discount
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: #6b7280;">
                                <span>Starter: {{ number_format($airtime['starter_discount'], 1) }}%</span>
                                <span style="color: #059669; font-weight: 600;">
                                    Pro: {{ number_format($airtime['pro_discount'], 1) }}% (+{{ number_format($airtime['extra_discount'], 1) }}%)
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ======================================================== --}}
        {{-- TAB 3: IDENTITY SERVICES --}}
        {{-- ======================================================== --}}
        @if($activeTab === 'identity' || $activeTab === 'utilities')
            <div class="rates-main-panel">
                <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb;" class="dark:border-gray-800">
                    <div style="font-size: 0.9375rem; font-weight: 600; color: #111827;" class="dark:text-white">
                        Identity Verification Services & Official Printable Slips
                    </div>
                    <div style="font-size: 0.78rem; color: #6b7280; margin-top: 2px;">
                        Wholesale deduction cost per verified document slip generated for NIN and BVN search requests.
                    </div>
                </div>

                {{-- DESKTOP VIEW --}}
                <div class="rates-table-desktop">
                    <table class="rates-grid-table">
                        <thead>
                            <tr>
                                <th>Identity Service</th>
                                <th>Printable Slip / Document</th>
                                <th>Wholesale Cost</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->identityServicesList as $item)
                                @php
                                    $isNin = str_contains(strtolower($item['service_name']), 'nin');
                                @endphp
                                <tr>
                                    <td>
                                        <span class="rates-chip {{ $isNin ? 'rates-tag-pill-pro' : '' }}" style="font-weight: 700;">
                                            {{ $item['service_name'] }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 600; color: #111827;" class="dark:text-white">
                                        {{ $item['name'] }}
                                    </td>
                                    <td style="font-family: monospace; font-weight: 700; color: #111827;" class="dark:text-white">
                                        ₦{{ number_format($item['wholesale_cost'], 2) }}
                                    </td>
                                    <td style="color: #6b7280; font-size: 0.78rem;">
                                        {{ $item['description'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW --}}
                <div class="rates-list-mobile">
                    @foreach($this->identityServicesList as $item)
                        @php
                            $isNin = str_contains(strtolower($item['service_name']), 'nin');
                        @endphp
                        <div class="rates-card-row">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                    <span class="rates-chip {{ $isNin ? 'rates-tag-pill-pro' : '' }}" style="font-weight: 700;">
                                        {{ $item['service_name'] }}
                                    </span>
                                    <span style="font-weight: 600; font-size: 0.875rem; color: #111827;" class="dark:text-white">
                                        {{ $item['name'] }}
                                    </span>
                                </div>
                                <span style="font-family: monospace; font-size: 0.8125rem; font-weight: 700; color: #111827;" class="dark:text-white">
                                    ₦{{ number_format($item['wholesale_cost'], 2) }}
                                </span>
                            </div>
                            <div style="font-size: 0.75rem; color: #6b7280;">
                                {{ $item['description'] }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ADDITIONAL IDENTITY PORTAL SERVICES --}}
                @if($this->additionalIdentityServices->isNotEmpty())
                    <div style="padding: 10px 16px; background-color: #f9fafb; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;" class="dark:bg-gray-800 dark:border-gray-700">
                        Other Identity & Bio-Data Services
                    </div>
                    <div class="rates-list-mobile" style="display: block;">
                        @foreach($this->additionalIdentityServices as $svc)
                            <div class="rates-card-row" style="background-color: transparent;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span class="rates-chip" style="font-weight: 700;">Identity Portal</span>
                                        <span style="font-weight: 600; font-size: 0.875rem; color: #111827;" class="dark:text-white">{{ $svc->name }}</span>
                                    </div>
                                    <span class="rates-tag-pill-pro" style="font-size: 0.7rem; font-weight: 600; padding: 2px 8px; border-radius: 9999px;">
                                        Storefront Service
                                    </span>
                                </div>
                                <div style="font-size: 0.75rem; color: #6b7280;">
                                    {{ $svc->description }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- SUBTLE ENTERPRISE FOOTNOTE --}}
        <div style="padding: 12px 16px; border-radius: 8px; border: 1px dashed #e5e7eb; background-color: #ffffff; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;" class="dark:border-gray-800 dark:bg-gray-900">
            <div style="font-size: 0.8125rem; color: #6b7280;">
                <strong style="color: #111827;" class="dark:text-white">High Volume & API:</strong>
                Processing over 2,000 GB monthly or connecting via direct API?
            </div>
            <a
                href="mailto:support@affanhub.com?subject=Enterprise%20Wholesale%20Rates%20Inquiry"
                style="font-size: 0.8125rem; font-weight: 600; color: #111827; text-decoration: underline;"
                class="dark:text-white"
            >
                Contact Sales for Enterprise Custom Rates &rarr;
            </a>
        </div>
    </div>
</x-filament-panels::page>
