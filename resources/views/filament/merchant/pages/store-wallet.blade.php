<x-filament-panels::page>
    @php
        $store = \Filament\Facades\Filament::getTenant();
        $mainWallet = $store?->mainWallet();
        $profitWallet = $store?->profitWallet();
        $balance = (float) ($mainWallet?->balance ?? 0.00);
        $profitBalance = (float) ($profitWallet?->balance ?? 0.00);
        $virtualAccount = $store ? \App\Models\VirtualAccount::where('holder_type', get_class($store))->where('holder_id', $store->id)->first() : null;
        $transactions = $mainWallet?->transactions()->latest()->take(15)->get() ?? collect();
        $settlement = $store ? \App\Models\SettlementAccount::where('store_id', $store->id)->latest()->first() : null;
        $withdrawals = $store ? \App\Models\Withdrawal::where('store_id', $store->id)->latest()->take(15)->get() : collect();
    @endphp

    <style>
        .wallet-page-wrap {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
            font-family: inherit;
        }

        /* NAVIGATION TABS (MATCHING WHOLESALE-RATES / SETTINGS) */
        .wallet-nav-tabs {
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
            .wallet-nav-tabs {
                display: inline-flex;
                width: fit-content;
            }
        }

        .dark .wallet-nav-tabs {
            background-color: #1f2937;
            border-color: #374151;
        }

        .wallet-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
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
            .wallet-tab-btn {
                flex: 0 0 auto;
            }
        }

        .wallet-tab-btn:hover {
            color: #111827;
        }

        .dark .wallet-tab-btn:hover {
            color: #ffffff;
        }

        .wallet-tab-btn.active {
            background-color: #ffffff;
            color: #111827;
            font-weight: 600;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        }

        .dark .wallet-tab-btn.active {
            background-color: #111827;
            color: #ffffff;
        }

        /* CARD CONTAINERS */
        .wallet-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .dark .wallet-card {
            background-color: #111827;
            border-color: #1f2937;
        }

        /* BUTTONS */
        .wallet-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 8px;
            background-color: #111827;
            color: #ffffff;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .wallet-btn-primary:hover {
            background-color: #1f2937;
        }

        .dark .wallet-btn-primary {
            background-color: #f9fafb;
            color: #111827;
        }

        .dark .wallet-btn-primary:hover {
            background-color: #ffffff;
        }

        .wallet-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #374151;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .wallet-btn-secondary:hover {
            background-color: #f9fafb;
            border-color: #9ca3af;
        }

        .dark .wallet-btn-secondary {
            background-color: #1f2937;
            border-color: #374151;
            color: #d1d5db;
        }

        .dark .wallet-btn-secondary:hover {
            background-color: #273142;
            color: #ffffff;
        }

        /* PILLS & BADGES */
        .wallet-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #f3f4f6;
            color: #374151;
        }

        .dark .wallet-badge {
            background-color: #1f2937;
            color: #d1d5db;
        }

        .wallet-badge-success {
            background-color: #ecfdf5;
            color: #065f46;
        }

        .dark .wallet-badge-success {
            background-color: #064e3b;
            color: #a7f3d0;
        }

        .wallet-badge-warning {
            background-color: #fffbeb;
            color: #92400e;
        }

        .dark .wallet-badge-warning {
            background-color: #78350f;
            color: #fde68a;
        }

        .wallet-badge-danger {
            background-color: #fef2f2;
            color: #991b1b;
        }

        .dark .wallet-badge-danger {
            background-color: #7f1d1d;
            color: #fecaca;
        }

        /* DATA TABLES */
        .wallet-table-wrap {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
        }

        .dark .wallet-table-wrap {
            border-color: #1f2937;
        }

        .wallet-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
            text-align: left;
        }

        .wallet-table th {
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .dark .wallet-table th {
            background-color: #111827;
            color: #9ca3af;
            border-bottom-color: #1f2937;
        }

        .wallet-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #1f2937;
            white-space: nowrap;
        }

        .dark .wallet-table td {
            border-bottom-color: #1f2937;
            color: #e5e7eb;
        }

        .wallet-table tr:last-child td {
            border-bottom: none;
        }

        .wallet-table tr:hover td {
            background-color: #f9fafb;
        }

        .dark .wallet-table tr:hover td {
            background-color: #172033;
        }

        /* MODAL STYLES */
        .wallet-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .wallet-modal-box {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .dark .wallet-modal-box {
            background-color: #111827;
            border-color: #1f2937;
        }

        .wallet-input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #ffffff;
            color: #111827;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .wallet-input:focus {
            border-color: #111827;
            box-shadow: 0 0 0 1px #111827;
        }

        .dark .wallet-input {
            background-color: #1f2937;
            border-color: #374151;
            color: #ffffff;
        }

        .dark .wallet-input:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 1px #9ca3af;
        }

        .wallet-chip-btn {
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .wallet-chip-btn:hover {
            border-color: #111827;
            color: #111827;
            background-color: #ffffff;
        }

        .dark .wallet-chip-btn {
            background-color: #1f2937;
            border-color: #374151;
            color: #9ca3af;
        }

        .dark .wallet-chip-btn:hover {
            border-color: #ffffff;
            color: #ffffff;
        }
    </style>

    <div 
        class="wallet-page-wrap"
        x-data="{
            activeTab: @entangle('activeTab').live,
            init() {
                const getTabFromHash = () => {
                    const raw = (window.location.hash || '').replace('#', '').trim().toLowerCase();
                    if (!raw) return null;
                    const map = {
                        'overview': 'overview',
                        'wallets': 'overview',
                        'funding': 'overview',
                        'payouts': 'payouts',
                        'withdrawals': 'payouts',
                        'settlement': 'payouts',
                        'policy': 'policy',
                        'fee': 'policy',
                        'fees': 'policy',
                        'funding-fee': 'policy'
                    };
                    return map[raw] || null;
                };

                const initial = getTabFromHash();
                if (initial) {
                    this.activeTab = initial;
                } else if (this.activeTab) {
                    window.history.replaceState(null, '', '#' + this.activeTab);
                }

                window.addEventListener('hashchange', () => {
                    const next = getTabFromHash();
                    if (next && next !== this.activeTab) {
                        this.activeTab = next;
                    }
                });

                this.$watch('activeTab', (value) => {
                    if (value && window.location.hash.replace('#', '') !== value) {
                        window.history.replaceState(null, '', '#' + value);
                    }
                });
            },
            selectTab(tab) {
                this.activeTab = tab;
                window.history.pushState(null, '', '#' + tab);
            }
        }"
    >
        
        {{-- TOP NAVIGATION TABS --}}
        <div class="wallet-nav-tabs">
            <button 
                type="button" 
                @click="selectTab('overview')"
                :class="activeTab === 'overview' ? 'active' : ''"
                class="wallet-tab-btn {{ $activeTab === 'overview' ? 'active' : '' }}"
            >
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Wallets & Funding</span>
            </button>

            <button 
                type="button" 
                @click="selectTab('payouts')"
                :class="activeTab === 'payouts' ? 'active' : ''"
                class="wallet-tab-btn {{ $activeTab === 'payouts' ? 'active' : '' }}"
            >
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Profit Withdrawals & Settlement</span>
            </button>

            <button 
                type="button" 
                @click="selectTab('policy')"
                :class="activeTab === 'policy' ? 'active' : ''"
                class="wallet-tab-btn {{ $activeTab === 'policy' ? 'active' : '' }}"
            >
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span>Customer Funding Fee</span>
            </button>
        </div>

        {{-- TAB 1: WALLETS & FUNDING --}}
        @if($activeTab === 'overview')
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                {{-- BALANCES GRID --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    
                    {{-- Operating Balance Card --}}
                    <div class="wallet-card">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between;">
                            <div>
                                <span class="wallet-badge">Operating Balance</span>
                                <h3 style="font-size: 1.75rem; font-weight: 700; color: #111827; margin: 0.5rem 0 0.25rem 0;" class="dark:text-white">
                                    ₦{{ number_format($balance, 2) }}
                                </h3>
                                <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">
                                    Wholesale service execution (VTU, identity lookups, data & bills)
                                </p>
                            </div>
                            <span class="wallet-badge wallet-badge-success">Operational</span>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f3f4f6; padding-top: 0.75rem; margin-top: 0.25rem;" class="dark:border-gray-800">
                            <span style="font-size: 0.75rem; color: #6b7280;">Auto-funded via Virtual Bank</span>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #111827;" class="dark:text-white">Main Wallet</span>
                        </div>
                    </div>

                    {{-- Profit Wallet Card --}}
                    <div class="wallet-card">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between;">
                            <div>
                                <span class="wallet-badge wallet-badge-success">Store Profit Margin</span>
                                <h3 style="font-size: 1.75rem; font-weight: 700; color: #111827; margin: 0.5rem 0 0.25rem 0;" class="dark:text-white">
                                    ₦{{ number_format($profitBalance, 2) }}
                                </h3>
                                <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">
                                    Accumulated profit margins & retail customer markups
                                </p>
                            </div>
                            <button 
                                type="button" 
                                wire:click="openWithdrawalModal" 
                                class="wallet-btn-primary"
                            >
                                <span>Withdraw Profit</span>
                                <span>→</span>
                            </button>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f3f4f6; padding-top: 0.75rem; margin-top: 0.25rem;" class="dark:border-gray-800">
                            <span style="font-size: 0.75rem; color: #6b7280;">Payout Fee: ₦100.00 • Min: ₦1,000.00</span>
                            <button type="button" @click="selectTab('payouts')" style="background: none; border: none; padding: 0; color: #2563eb; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                View Payouts →
                            </button>
                        </div>
                    </div>

                </div>

                {{-- VIRTUAL ACCOUNT SECTION --}}
                <div class="wallet-card">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <h4 style="font-size: 0.9375rem; font-weight: 700; color: #111827; margin: 0;" class="dark:text-white">
                                Dedicated Store Virtual Account (Auto-Funding)
                            </h4>
                            <p style="font-size: 0.8125rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                                Transfer funds from any Nigerian banking app. Your Operating Wallet is credited automatically within seconds.
                            </p>
                        </div>
                    </div>

                    @if($virtualAccount)
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; background-color: #f9fafb; padding: 1rem; border-radius: 8px; border: 1px solid #e5e7eb;" class="dark:bg-gray-800 dark:border-gray-700">
                            <div>
                                <span style="font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; color: #6b7280;">Bank Name</span>
                                <div style="font-size: 0.9375rem; font-weight: 700; color: #111827; margin-top: 0.125rem;" class="dark:text-white">
                                    {{ $virtualAccount->bank_name }}
                                </div>
                            </div>
                            <div>
                                <span style="font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; color: #6b7280;">Account Number</span>
                                <div style="display: flex; align-items: center; gap: 6px; margin-top: 0.125rem;">
                                    <span style="font-size: 1.125rem; font-weight: 800; font-family: monospace; color: #111827; letter-spacing: 0.05em;" class="dark:text-white">
                                        {{ $virtualAccount->account_number }}
                                    </span>
                                    <button 
                                        type="button" 
                                        onclick="navigator.clipboard.writeText('{{ $virtualAccount->account_number }}'); $tooltip('Copied!', { timeout: 1500 });"
                                        class="wallet-chip-btn"
                                        title="Copy Account Number"
                                    >
                                        Copy
                                    </button>
                                </div>
                            </div>
                            <div>
                                <span style="font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; color: #6b7280;">Account Name</span>
                                <div style="font-size: 0.875rem; font-weight: 600; color: #111827; margin-top: 0.125rem;" class="dark:text-white">
                                    {{ $virtualAccount->account_name }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div style="background-color: #f9fafb; border: 1px dashed #d1d5db; border-radius: 8px; padding: 1.25rem; text-align: center;" class="dark:bg-gray-800 dark:border-gray-700">
                            <p style="font-size: 0.8125rem; color: #4b5563; margin-bottom: 0.75rem;" class="dark:text-gray-300">
                                You have not generated a dedicated virtual bank account for instant store auto-funding yet.
                            </p>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; max-width: 480px; margin: 0 auto;">
                                <select wire:model.live="kycType" class="wallet-input" style="width: auto;">
                                    <option value="nin">National Identity (NIN)</option>
                                    <option value="bvn">Bank Verification (BVN)</option>
                                </select>
                                <input type="text" wire:model="kycNumber" placeholder="11-digit KYC Number" class="wallet-input" style="flex: 1; min-width: 140px;" />
                                <button type="button" wire:click="generateVirtualAccount" class="wallet-btn-primary">
                                    Generate Account
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- RECENT TRANSACTIONS TABLE --}}
                <div class="wallet-card">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <h4 style="font-size: 0.9375rem; font-weight: 700; color: #111827; margin: 0;" class="dark:text-white">
                            Recent Operating Transactions
                        </h4>
                        <span style="font-size: 0.75rem; color: #6b7280;">Latest 15 events</span>
                    </div>

                    @if($transactions->isEmpty())
                        <div style="text-align: center; padding: 2rem 1rem; color: #6b7280; font-size: 0.8125rem;">
                            No recent transactions found on the operating wallet.
                        </div>
                    @else
                        <div class="wallet-table-wrap">
                            <table class="wallet-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $txn)
                                        <tr>
                                            <td style="color: #6b7280;">{{ $txn->created_at->format('M d, Y H:i') }}</td>
                                            <td style="font-family: monospace; font-weight: 600;">{{ $txn->reference ?? '—' }}</td>
                                            <td>{{ $txn->description ?? 'Wallet Transaction' }}</td>
                                            <td>
                                                @if($txn->type === 'credit')
                                                    <span class="wallet-badge wallet-badge-success">Credit</span>
                                                @else
                                                    <span class="wallet-badge wallet-badge-danger">Debit</span>
                                                @endif
                                            </td>
                                            <td style="font-weight: 700; color: {{ $txn->type === 'credit' ? '#059669' : '#111827' }};">
                                                {{ $txn->type === 'credit' ? '+' : '-' }}₦{{ number_format($txn->amount, 2) }}
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

        {{-- TAB 2: PROFIT WITHDRAWALS & SETTLEMENT --}}
        @if($activeTab === 'payouts')
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                {{-- TOP SUMMARY GRID --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    
                    {{-- Profit Payout Action Card --}}
                    <div class="wallet-card">
                        <span class="wallet-badge wallet-badge-success">Store Profit Balance</span>
                        <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-top: 0.25rem;">
                            <h3 style="font-size: 1.75rem; font-weight: 700; color: #111827; margin: 0;" class="dark:text-white">
                                ₦{{ number_format($profitBalance, 2) }}
                            </h3>
                        </div>
                        <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">
                            Net profit accrued from retail customer transactions and markups.
                        </p>

                        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.5rem; padding-top: 0.5rem; border-top: 1px solid #f3f4f6;" class="dark:border-gray-800">
                            <span style="font-size: 0.75rem; color: #6b7280;">Min: ₦1,000.00 • Fee: ₦100.00</span>
                            <button 
                                type="button" 
                                wire:click="openWithdrawalModal" 
                                class="wallet-btn-primary"
                            >
                                <span>Request Payout</span>
                                <span>→</span>
                            </button>
                        </div>
                    </div>

                    {{-- Settlement Bank Card --}}
                    <div class="wallet-card">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span class="wallet-badge">Settlement Destination</span>
                            @if($settlement)
                                @if($settlement->status === 'approved')
                                    <span class="wallet-badge wallet-badge-success">Verified & Active</span>
                                @elseif($settlement->status === 'pending')
                                    <span class="wallet-badge wallet-badge-warning">Pending Approval</span>
                                @else
                                    <span class="wallet-badge wallet-badge-danger">Rejected</span>
                                @endif
                            @else
                                <span class="wallet-badge wallet-badge-warning">Not Configured</span>
                            @endif
                        </div>

                        @if($settlement)
                            <div>
                                <div style="font-size: 1rem; font-weight: 700; color: #111827;" class="dark:text-white">
                                    {{ $settlement->bank_name }}
                                </div>
                                <div style="font-size: 0.875rem; font-family: monospace; font-weight: 600; color: #4b5563; margin-top: 2px;" class="dark:text-gray-300">
                                    {{ $settlement->account_number }}
                                </div>
                                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 2px;">
                                    {{ $settlement->account_name }}
                                </div>
                            </div>
                        @else
                            <p style="font-size: 0.8125rem; color: #6b7280; margin: 0;">
                                Add your Nigerian commercial bank account to receive automatic or approved profit payouts.
                            </p>
                        @endif

                        <div style="display: flex; justify-content: flex-end; padding-top: 0.5rem; border-top: 1px solid #f3f4f6;" class="dark:border-gray-800">
                            <button 
                                type="button" 
                                wire:click="openSettlementModal" 
                                class="wallet-btn-secondary"
                                style="font-size: 0.75rem; padding: 0.375rem 0.75rem;"
                            >
                                {{ $settlement ? 'Update Bank Account' : 'Add Bank Account' }}
                            </button>
                        </div>
                    </div>

                </div>

                {{-- PAYOUT REQUESTS HISTORY TABLE --}}
                <div class="wallet-card">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h4 style="font-size: 0.9375rem; font-weight: 700; color: #111827; margin: 0;" class="dark:text-white">
                                Profit Payout History
                            </h4>
                            <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                                Log of all requested withdrawals to your settlement bank account.
                            </p>
                        </div>
                    </div>

                    @if($withdrawals->isEmpty())
                        <div style="text-align: center; padding: 2.5rem 1rem; color: #6b7280; font-size: 0.8125rem;">
                            No profit withdrawals requested yet. When you request a payout, it will appear here.
                        </div>
                    @else
                        <div class="wallet-table-wrap">
                            <table class="wallet-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Requested</th>
                                        <th>Fee</th>
                                        <th>Net Payout</th>
                                        <th>Bank Destination</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals as $w)
                                        <tr>
                                            <td style="color: #6b7280;">{{ $w->created_at->format('M d, Y H:i') }}</td>
                                            <td style="font-family: monospace; font-weight: 600;">{{ $w->reference }}</td>
                                            <td style="font-weight: 600;">₦{{ number_format($w->amount, 2) }}</td>
                                            <td style="color: #ef4444;">-₦{{ number_format($w->fee ?? 100, 2) }}</td>
                                            <td style="font-weight: 700; color: #059669;">
                                                ₦{{ number_format($w->net_amount ?? max(0, $w->amount - 100), 2) }}
                                            </td>
                                            <td>
                                                <div style="font-weight: 500;">{{ $w->bank_name }}</div>
                                                <div style="font-size: 0.6875rem; color: #6b7280; font-family: monospace;">{{ $w->account_number }}</div>
                                            </td>
                                            <td>
                                                @if($w->status === 'completed')
                                                    <span class="wallet-badge wallet-badge-success">Completed</span>
                                                @elseif($w->status === 'approved')
                                                    <span class="wallet-badge wallet-badge-success">Approved</span>
                                                @elseif($w->status === 'rejected')
                                                    <span class="wallet-badge wallet-badge-danger">Rejected</span>
                                                @else
                                                    <span class="wallet-badge wallet-badge-warning">Pending</span>
                                                @endif
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

        {{-- TAB 3: CUSTOMER FUNDING FEE POLICY --}}
        @if($activeTab === 'policy')
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                {{-- OVERVIEW & PLATFORM FEE TRANSPARENCY CARDS --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                    
                    {{-- Platform Gateway Cost Card --}}
                    <div class="wallet-card">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span class="wallet-badge">Platform Base Gateway Fee</span>
                            <span class="wallet-badge wallet-badge-warning">Cost to Store</span>
                        </div>
                        <div>
                            <h3 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0.25rem 0;" class="dark:text-white">
                                {{ $this->getPlatformFeePercent() }}% <span style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">(Max ₦{{ number_format($this->getPlatformFeeCap(), 2) }})</span>
                            </h3>
                            <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">
                                The standard processing fee charged by our banking infrastructure provider on incoming customer virtual account deposits.
                            </p>
                        </div>
                        <div style="border-top: 1px solid #f3f4f6; padding-top: 0.5rem; font-size: 0.75rem; color: #6b7280;" class="dark:border-gray-800">
                            Capped at ₦{{ number_format($this->getPlatformFeeCap(), 0) }} for high-volume deposits.
                        </div>
                    </div>

                    {{-- Current Store Policy Card --}}
                    <div class="wallet-card">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span class="wallet-badge">Your Active Customer Policy</span>
                            @if($customerFeeType === 'free')
                                <span class="wallet-badge wallet-badge-danger">Store Pays Fee</span>
                            @elseif($customerFeeType === 'percentage' && $customerFeeAmount == $this->getPlatformFeePercent() && $customerFeeCap == $this->getPlatformFeeCap())
                                <span class="wallet-badge wallet-badge-success">Standard Protected</span>
                            @elseif($customerFeeType === 'percentage' && $customerFeeAmount > $this->getPlatformFeePercent())
                                <span class="wallet-badge wallet-badge-success">Profitable Markup</span>
                            @else
                                <span class="wallet-badge">Custom Config</span>
                            @endif
                        </div>
                        <div>
                            <h3 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0.25rem 0;" class="dark:text-white">
                                @if($customerFeeType === 'free')
                                    0% (Free Funding)
                                @elseif($customerFeeType === 'flat')
                                    ₦{{ number_format($customerFeeAmount, 2) }} Flat Fee
                                @else
                                    {{ $customerFeeAmount }}% <span style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">(Max ₦{{ number_format($customerFeeCap, 2) }})</span>
                                @endif
                            </h3>
                            <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">
                                @if($customerFeeType === 'free')
                                    Your store absorbs all gateway fees from your Operating Balance. Customers receive 100% of their deposit.
                                @elseif($customerFeeType === 'flat')
                                    Customers pay a fixed ₦{{ number_format($customerFeeAmount, 2) }} charge per bank transfer regardless of the amount.
                                @else
                                    Customers pay a {{ $customerFeeAmount }}% transfer fee (maximum ₦{{ number_format($customerFeeCap, 2) }}).
                                @endif
                            </p>
                        </div>
                        <div style="border-top: 1px solid #f3f4f6; padding-top: 0.5rem; font-size: 0.75rem; color: #6b7280;" class="dark:border-gray-800">
                            Visible to your customers on their storefront funding screen.
                        </div>
                    </div>

                </div>

                {{-- INFO ALERT --}}
                <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 1rem 1.25rem; display: flex; align-items: flex-start; gap: 0.75rem;" class="dark:bg-blue-950/40 dark:border-blue-900">
                    <svg style="width: 20px; height: 20px; color: #2563eb; flex-shrink: 0; margin-top: 2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div style="font-size: 0.8125rem; color: #1e40af; line-height: 1.5;" class="dark:text-blue-300">
                        <strong style="font-weight: 700;">Platform Default (1% capped at ₦1,000):</strong>
                        We automatically configure your customer funding fee to match the platform's base gateway cost (1% capped at ₦1,000). This guarantees that your business never loses money when retail customers deposit funds into their wallets.
                    </div>
                </div>

                {{-- SETTINGS FORM CARD --}}
                <div class="wallet-card">
                    <div>
                        <h4 style="font-size: 0.9375rem; font-weight: 700; color: #111827; margin: 0;" class="dark:text-white">
                            Configure Customer Funding Fee Policy
                        </h4>
                        <p style="font-size: 0.8125rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                            Choose how gateway processing charges are handled on your store.
                        </p>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 1.125rem; max-width: 580px; margin-top: 0.5rem;">
                        <div>
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                                Fee Policy Type
                            </label>
                            <select wire:model.live="customerFeeType" class="wallet-input">
                                <option value="percentage">Percentage Fee with Maximum Cap (Recommended - Platform Standard)</option>
                                <option value="flat">Flat Fee per Funding Deposit (Fixed ₦ Amount)</option>
                                <option value="free">Free (Store absorbs all gateway charges)</option>
                            </select>
                            @error('customerFeeType')
                                <span style="display: block; font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($customerFeeType === 'flat')
                            <div>
                                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                                    Flat Fee Amount (₦)
                                </label>
                                <input type="number" step="0.01" min="0" wire:model="customerFeeAmount" class="wallet-input" placeholder="e.g. 50" />
                                <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 0.5rem;">
                                    <button type="button" wire:click="$set('customerFeeAmount', 50.0)" class="wallet-chip-btn">₦50.00</button>
                                    <button type="button" wire:click="$set('customerFeeAmount', 100.0)" class="wallet-chip-btn">₦100.00</button>
                                    <button type="button" wire:click="$set('customerFeeAmount', 150.0)" class="wallet-chip-btn">₦150.00</button>
                                    <button type="button" wire:click="$set('customerFeeAmount', 200.0)" class="wallet-chip-btn">₦200.00</button>
                                </div>
                                @error('customerFeeAmount')
                                    <span style="display: block; font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif($customerFeeType === 'percentage')
                            <div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                                            Fee Percentage (%)
                                        </label>
                                        <input type="number" step="0.01" min="0" max="100" wire:model="customerFeeAmount" class="wallet-input" placeholder="e.g. 1.0" />
                                        @error('customerFeeAmount')
                                            <span style="display: block; font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                                            Maximum Cap (₦)
                                        </label>
                                        <input type="number" step="0.01" min="0" wire:model="customerFeeCap" class="wallet-input" placeholder="e.g. 1000" />
                                        @error('customerFeeCap')
                                            <span style="display: block; font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 0.5rem;">
                                    <button type="button" wire:click="$set('customerFeeAmount', 1.0); $set('customerFeeCap', 1000.0);" class="wallet-chip-btn">
                                        1.0% (Cap ₦1,000) • Platform Standard
                                    </button>
                                    <button type="button" wire:click="$set('customerFeeAmount', 1.5); $set('customerFeeCap', 1500.0);" class="wallet-chip-btn">
                                        1.5% (Cap ₦1,500) • +0.5% Store Profit
                                    </button>
                                    <button type="button" wire:click="$set('customerFeeAmount', 2.0); $set('customerFeeCap', 2000.0);" class="wallet-chip-btn">
                                        2.0% (Cap ₦2,000) • +1.0% Store Profit
                                    </button>
                                </div>
                            </div>
                        @else
                            <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 0.875rem 1rem; color: #991b1b; font-size: 0.8125rem;" class="dark:bg-red-950/40 dark:border-red-900 dark:text-red-300">
                                <strong>Caution:</strong> When set to Free, your customers are credited 100% of their deposit, and your store absorbs the 1% platform fee (capped at ₦1,000) from your Operating Balance on every deposit.
                            </div>
                        @endif

                        <div style="display: flex; align-items: center; gap: 0.75rem; padding-top: 0.5rem; flex-wrap: wrap;">
                            <button 
                                type="button" 
                                wire:click="saveFundingFeeSettings" 
                                wire:loading.attr="disabled"
                                class="wallet-btn-primary"
                            >
                                <span wire:loading.remove wire:target="saveFundingFeeSettings">Save Policy Changes</span>
                                <span wire:loading wire:target="saveFundingFeeSettings">Saving...</span>
                            </button>

                            <button 
                                type="button" 
                                wire:click="resetToPlatformDefault" 
                                class="wallet-btn-secondary"
                            >
                                Reset to Platform Default (1% Cap ₦1,000)
                            </button>
                        </div>
                    </div>
                </div>

                {{-- FREQUENTLY ASKED QUESTIONS (FAQ) --}}
                <div class="wallet-card">
                    <div>
                        <h4 style="font-size: 0.9375rem; font-weight: 700; color: #111827; margin: 0;" class="dark:text-white">
                            Frequently Asked Questions (FAQ)
                        </h4>
                        <p style="font-size: 0.8125rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                            Everything you need to know about customer wallet deposits and funding fees.
                        </p>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem;" x-data="{ openFaq: null }">
                        
                        {{-- FAQ 1 --}}
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.875rem 1rem; background-color: #f9fafb;" class="dark:border-gray-800 dark:bg-gray-800/50">
                            <button 
                                type="button" 
                                @click="openFaq = openFaq === 1 ? null : 1" 
                                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: none; border: none; padding: 0; text-align: left; cursor: pointer;"
                            >
                                <span style="font-size: 0.875rem; font-weight: 600; color: #111827;" class="dark:text-white">
                                    What is the Customer Funding Fee?
                                </span>
                                <span style="font-size: 1.125rem; line-height: 1; color: #6b7280;" x-text="openFaq === 1 ? '−' : '+'"></span>
                            </button>
                            <div x-show="openFaq === 1" x-collapse style="margin-top: 0.5rem; font-size: 0.8125rem; line-height: 1.5; color: #4b5563;" class="dark:text-gray-300">
                                When your end-customers fund their virtual wallets via bank transfer, the banking gateway provider charges a transaction processing fee. This setting allows you to choose whether your store absorbs this fee from your operating balance or passes it through to the customer during deposit.
                            </div>
                        </div>

                        {{-- FAQ 2 --}}
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.875rem 1rem; background-color: #f9fafb;" class="dark:border-gray-800 dark:bg-gray-800/50">
                            <button 
                                type="button" 
                                @click="openFaq = openFaq === 2 ? null : 2" 
                                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: none; border: none; padding: 0; text-align: left; cursor: pointer;"
                            >
                                <span style="font-size: 0.875rem; font-weight: 600; color: #111827;" class="dark:text-white">
                                    What fee does AffanHub charge my store?
                                </span>
                                <span style="font-size: 1.125rem; line-height: 1; color: #6b7280;" x-text="openFaq === 2 ? '−' : '+'"></span>
                            </button>
                            <div x-show="openFaq === 2" x-collapse style="margin-top: 0.5rem; font-size: 0.8125rem; line-height: 1.5; color: #4b5563;" class="dark:text-gray-300">
                                AffanHub's payment gateway charges exactly <strong>1% per deposit, capped at ₦1,000.00</strong>.
                                <ul style="margin: 0.5rem 0 0 1.25rem; padding: 0; list-style-type: disc;">
                                    <li>On a <strong>₦2,000</strong> deposit: the fee is <strong>₦20.00</strong>.</li>
                                    <li>On a <strong>₦10,000</strong> deposit: the fee is <strong>₦100.00</strong>.</li>
                                    <li>On a <strong>₦200,000</strong> deposit: 1% would be ₦2,000, but because of the cap, the fee is only <strong>₦1,000.00</strong>.</li>
                                </ul>
                            </div>
                        </div>

                        {{-- FAQ 3 --}}
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.875rem 1rem; background-color: #f9fafb;" class="dark:border-gray-800 dark:bg-gray-800/50">
                            <button 
                                type="button" 
                                @click="openFaq = openFaq === 3 ? null : 3" 
                                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: none; border: none; padding: 0; text-align: left; cursor: pointer;"
                            >
                                <span style="font-size: 0.875rem; font-weight: 600; color: #111827;" class="dark:text-white">
                                    Why was my store automatically set to 1% capped at ₦1,000?
                                </span>
                                <span style="font-size: 1.125rem; line-height: 1; color: #6b7280;" x-text="openFaq === 3 ? '−' : '+'"></span>
                            </button>
                            <div x-show="openFaq === 3" x-collapse style="margin-top: 0.5rem; font-size: 0.8125rem; line-height: 1.5; color: #4b5563;" class="dark:text-gray-300">
                                Previously, store deposit policy defaulted to "Free", which meant merchants were unknowingly absorbing all gateway processing fees whenever their customers funded their accounts. To protect your business finances and eliminate negative balances, the system now automatically aligns your deposit policy with the platform's standard 1% fee (capped at ₦1,000).
                            </div>
                        </div>

                        {{-- FAQ 4 --}}
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.875rem 1rem; background-color: #f9fafb;" class="dark:border-gray-800 dark:bg-gray-800/50">
                            <button 
                                type="button" 
                                @click="openFaq = openFaq === 4 ? null : 4" 
                                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: none; border: none; padding: 0; text-align: left; cursor: pointer;"
                            >
                                <span style="font-size: 0.875rem; font-weight: 600; color: #111827;" class="dark:text-white">
                                    Can I change my policy back to Free or Flat Fee?
                                </span>
                                <span style="font-size: 1.125rem; line-height: 1; color: #6b7280;" x-text="openFaq === 4 ? '−' : '+'"></span>
                            </button>
                            <div x-show="openFaq === 4" x-collapse style="margin-top: 0.5rem; font-size: 0.8125rem; line-height: 1.5; color: #4b5563;" class="dark:text-gray-300">
                                Yes, you have full control over your storefront pricing. If you choose <strong>Free</strong>, your customers will not be charged anything, but your store's Operating Balance will pay the 1% platform fee. If you choose <strong>Flat Fee</strong>, a fixed amount (e.g. ₦50 or ₦100) will be deducted on every deposit regardless of size.
                            </div>
                        </div>

                        {{-- FAQ 5 --}}
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.875rem 1rem; background-color: #f9fafb;" class="dark:border-gray-800 dark:bg-gray-800/50">
                            <button 
                                type="button" 
                                @click="openFaq = openFaq === 5 ? null : 5" 
                                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: none; border: none; padding: 0; text-align: left; cursor: pointer;"
                            >
                                <span style="font-size: 0.875rem; font-weight: 600; color: #111827;" class="dark:text-white">
                                    Can I earn extra profit from customer deposits?
                                </span>
                                <span style="font-size: 1.125rem; line-height: 1; color: #6b7280;" x-text="openFaq === 5 ? '−' : '+'"></span>
                            </button>
                            <div x-show="openFaq === 5" x-collapse style="margin-top: 0.5rem; font-size: 0.8125rem; line-height: 1.5; color: #4b5563;" class="dark:text-gray-300">
                                Yes! If you set your customer fee higher than the 1% platform fee (for example, 1.5% with a ₦1,500 cap), the 1.0% platform fee covers the bank gateway, and the remaining <strong>0.5% surplus is automatically credited straight to your Store Profit Wallet</strong> as pure profit.
                            </div>
                        </div>

                        {{-- FAQ 6 --}}
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.875rem 1rem; background-color: #f9fafb;" class="dark:border-gray-800 dark:bg-gray-800/50">
                            <button 
                                type="button" 
                                @click="openFaq = openFaq === 6 ? null : 6" 
                                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: none; border: none; padding: 0; text-align: left; cursor: pointer;"
                            >
                                <span style="font-size: 0.875rem; font-weight: 600; color: #111827;" class="dark:text-white">
                                    What does the Maximum Fee Cap do?
                                </span>
                                <span style="font-size: 1.125rem; line-height: 1; color: #6b7280;" x-text="openFaq === 6 ? '−' : '+'"></span>
                            </button>
                            <div x-show="openFaq === 6" x-collapse style="margin-top: 0.5rem; font-size: 0.8125rem; line-height: 1.5; color: #4b5563;" class="dark:text-gray-300">
                                The fee cap protects high-value depositors from excessive fees. For example, without a cap, a customer funding ₦500,000 would be charged ₦5,000. With the ₦1,000 cap, the fee stops at ₦1,000, encouraging your big customers and agents to fund larger amounts without hesitation.
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        @endif

    </div>

    {{-- WITHDRAWAL MODAL --}}
    @if($withdrawalModalOpen)
        <div class="wallet-modal-overlay">
            <div class="wallet-modal-box">
                
                {{-- Header --}}
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb;" class="dark:border-gray-800">
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0;" class="dark:text-white">
                            Withdraw Store Profit
                        </h3>
                        <p style="font-size: 0.75rem; color: #6b7280; margin: 0.125rem 0 0 0;">
                            Transfer profit balance to your commercial bank.
                        </p>
                    </div>
                    <button type="button" wire:click="closeWithdrawalModal" style="background: none; border: none; font-size: 1.25rem; line-height: 1; color: #9ca3af; cursor: pointer;">
                        &times;
                    </button>
                </div>

                {{-- Body --}}
                <div style="padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                    
                    {{-- Balance Display --}}
                    <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.875rem 1rem; display: flex; align-items: center; justify-content: space-between;" class="dark:bg-gray-800 dark:border-gray-700">
                        <span style="font-size: 0.8125rem; color: #6b7280;">Available Profit Balance</span>
                        <span style="font-size: 1.125rem; font-weight: 700; color: #111827;" class="dark:text-white">
                            ₦{{ number_format($profitBalance, 2) }}
                        </span>
                    </div>

                    {{-- Warning if no bank approved --}}
                    @if(! $settlement || $settlement->status !== 'approved')
                        <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 0.875rem 1rem; font-size: 0.8125rem; color: #92400e;" class="dark:bg-amber-950/40 dark:border-amber-800 dark:text-amber-300">
                            <strong>Notice:</strong> 
                            @if(! $settlement)
                                You have not linked a settlement bank account yet. Please add your bank details before processing a payout.
                            @else
                                Your bank account is currently <strong>Pending Super Admin Approval</strong>. You can submit your request once approved.
                            @endif
                        </div>
                    @endif

                    {{-- Amount Input --}}
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.375rem;">
                            <label style="font-size: 0.8125rem; font-weight: 600; color: #374151;" class="dark:text-gray-300">
                                Payout Amount (₦)
                            </label>
                            <span style="font-size: 0.75rem; color: #6b7280;">
                                Min: ₦1,000.00 • Fee: ₦100.00
                            </span>
                        </div>

                        <input 
                            type="number" 
                            step="0.01" 
                            min="1000"
                            wire:model.live="withdrawalAmount" 
                            placeholder="Enter amount (Min ₦1,000.00)" 
                            class="wallet-input"
                        />

                        {{-- Quick preset chips --}}
                        <div style="display: flex; flex-wrap: wrap; gap: 0.375rem; margin-top: 0.5rem;">
                            <button type="button" wire:click="setWithdrawalAmount(1000)" class="wallet-chip-btn">₦1,000</button>
                            <button type="button" wire:click="setWithdrawalAmount(5000)" class="wallet-chip-btn">₦5,000</button>
                            <button type="button" wire:click="setWithdrawalAmount(10000)" class="wallet-chip-btn">₦10,000</button>
                            @if($profitBalance > 0)
                                <button type="button" wire:click="setWithdrawalAmount({{ $profitBalance }})" class="wallet-chip-btn">
                                    All Profit (₦{{ number_format($profitBalance, 2) }})
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Dynamic Breakdown --}}
                    @php
                        $inputVal = (float) ($withdrawalAmount ?? 0);
                        $calcFee = $payoutFee;
                        $calcNet = max(0, $inputVal - $calcFee);
                    @endphp

                    @if($inputVal > 0)
                        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.875rem 1rem; display: flex; flex-direction: column; gap: 0.375rem; font-size: 0.8125rem;" class="dark:bg-gray-800 dark:border-gray-700">
                            <div style="display: flex; justify-content: space-between; color: #6b7280;">
                                <span>Requested Payout:</span>
                                <span style="font-weight: 600; color: #111827;" class="dark:text-white">₦{{ number_format($inputVal, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; color: #ef4444;">
                                <span>Payout Transfer Fee:</span>
                                <span>-₦{{ number_format($calcFee, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-weight: 700; color: #059669; border-top: 1px solid #e5e7eb; padding-top: 0.375rem; margin-top: 0.125rem;" class="dark:border-gray-700">
                                <span>Net Credit to Bank:</span>
                                <span>₦{{ number_format($calcNet, 2) }}</span>
                            </div>

                            @if($inputVal < 1000)
                                <div style="color: #d97706; font-size: 0.75rem; font-weight: 600; margin-top: 0.25rem;">
                                    ⚠️ Minimum payout amount is ₦1,000.00.
                                </div>
                            @elseif($inputVal > $profitBalance)
                                <div style="color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-top: 0.25rem;">
                                    ⚠️ Amount exceeds your available profit of ₦{{ number_format($profitBalance, 2) }}.
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Destination Account Summary --}}
                    @if($settlement)
                        <div style="border-top: 1px solid #f3f4f6; padding-top: 0.75rem; font-size: 0.75rem; color: #6b7280;" class="dark:border-gray-800">
                            Destination: <strong style="color: #111827;" class="dark:text-white">{{ $settlement->account_name }}</strong> ({{ $settlement->bank_name }} - {{ $settlement->account_number }})
                        </div>
                    @endif

                </div>

                {{-- Footer --}}
                <div style="padding: 1rem 1.5rem; background-color: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 0.5rem;" class="dark:bg-gray-800 dark:border-gray-700">
                    <button type="button" wire:click="closeWithdrawalModal" class="wallet-btn-secondary">
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        wire:click="requestProfitWithdrawal" 
                        class="wallet-btn-primary"
                        @if(!$settlement || $settlement->status !== 'approved' || $inputVal < 1000 || $inputVal > $profitBalance) disabled style="opacity: 0.6; cursor: not-allowed;" @endif
                    >
                        Confirm & Withdraw
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- SETTLEMENT ACCOUNT REGISTRATION MODAL --}}
    @if($settlementModalOpen)
        <div class="wallet-modal-overlay">
            <div class="wallet-modal-box">
                
                {{-- Header --}}
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid #e5e7eb;" class="dark:border-gray-800">
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0;" class="dark:text-white">
                            {{ $settlement ? 'Update Settlement Bank' : 'Add Settlement Bank' }}
                        </h3>
                        <p style="font-size: 0.75rem; color: #6b7280; margin: 0.125rem 0 0 0;">
                            Account details will be verified by Super Admins.
                        </p>
                    </div>
                    <button type="button" wire:click="closeSettlementModal" style="background: none; border: none; font-size: 1.25rem; line-height: 1; color: #9ca3af; cursor: pointer;">
                        &times;
                    </button>
                </div>

                {{-- Body --}}
                <div style="padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                            Bank Name
                        </label>
                        <select wire:model="settlementBankName" class="wallet-input">
                            <option value="">Select your bank...</option>
                            @foreach(\App\Filament\Merchant\Pages\StoreWallet::getNigerianBanks() as $bankKey => $bankLabel)
                                <option value="{{ $bankKey }}">{{ $bankLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                            NUBAN Account Number (10 digits)
                        </label>
                        <input type="text" maxlength="10" wire:model="settlementAccountNumber" placeholder="e.g. 0123456789" class="wallet-input" />
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                            Account Holder Name (Must match bank records)
                        </label>
                        <input type="text" wire:model="settlementAccountName" placeholder="e.g. John Doe / Business Name" class="wallet-input" />
                    </div>
                </div>

                {{-- Footer --}}
                <div style="padding: 1rem 1.5rem; background-color: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 0.5rem;" class="dark:bg-gray-800 dark:border-gray-700">
                    <button type="button" wire:click="closeSettlementModal" class="wallet-btn-secondary">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveSettlementAccount" class="wallet-btn-primary">
                        Save Bank Details
                    </button>
                </div>

            </div>
        </div>
    @endif

</x-filament-panels::page>
