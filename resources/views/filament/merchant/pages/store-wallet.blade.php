<x-filament-panels::page>
    @php
        $store = \Filament\Facades\Filament::getTenant();
        $mainWallet = $store?->mainWallet();
        $profitWallet = $store?->profitWallet();
        $balance = (float) ($mainWallet?->balance ?? 0.00);
        $profitBalance = (float) ($profitWallet?->balance ?? 0.00);
        $virtualAccount = \App\Models\VirtualAccount::where('holder_type', get_class($store))->where('holder_id', $store?->id)->first();
        $transactions = $mainWallet?->transactions()->latest()->take(15)->get() ?? collect();
        $settlement = \App\Models\SettlementAccount::where('store_id', $store?->id)->latest()->first();
        $withdrawals = \App\Models\Withdrawal::where('store_id', $store?->id)->latest()->take(15)->get() ?? collect();
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

    <div class="wallet-page-wrap">
        
        {{-- TOP NAVIGATION TABS --}}
        <div class="wallet-nav-tabs">
            <button 
                type="button" 
                wire:click="setTab('overview')"
                class="wallet-tab-btn {{ $activeTab === 'overview' ? 'active' : '' }}"
            >
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Wallets & Funding</span>
            </button>

            <button 
                type="button" 
                wire:click="setTab('payouts')"
                class="wallet-tab-btn {{ $activeTab === 'payouts' ? 'active' : '' }}"
            >
                <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Profit Withdrawals & Settlement</span>
            </button>

            <button 
                type="button" 
                wire:click="setTab('policy')"
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
                            <button type="button" wire:click="setTab('payouts')" style="background: none; border: none; padding: 0; color: #2563eb; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
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
            <div class="wallet-card">
                <div>
                    <h4 style="font-size: 0.9375rem; font-weight: 700; color: #111827; margin: 0;" class="dark:text-white">
                        Customer Funding Fee Settings
                    </h4>
                    <p style="font-size: 0.8125rem; color: #6b7280; margin: 0.25rem 0 0 0;">
                        Configure whether to absorb or pass through bank gateway processing charges when your retail customers fund their accounts.
                    </p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem; max-width: 560px; margin-top: 0.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                            Fee Policy
                        </label>
                        <select wire:model.live="customerFeeType" class="wallet-input">
                            <option value="free">Free (Store absorbs all gateway charges)</option>
                            <option value="flat">Flat Fee per Funding Deposit</option>
                            <option value="percentage">Percentage Fee with Maximum Cap</option>
                        </select>
                    </div>

                    @if($customerFeeType === 'flat')
                        <div>
                            <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                                Flat Fee Amount (₦)
                            </label>
                            <input type="number" step="0.01" wire:model="customerFeeAmount" class="wallet-input" placeholder="e.g. 50" />
                        </div>
                    @elseif($customerFeeType === 'percentage')
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                                    Fee Percentage (%)
                                </label>
                                <input type="number" step="0.01" wire:model="customerFeeAmount" class="wallet-input" placeholder="e.g. 1.5" />
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;" class="dark:text-gray-300">
                                    Maximum Cap (₦)
                                </label>
                                <input type="number" step="0.01" wire:model="customerFeeCap" class="wallet-input" placeholder="e.g. 100" />
                            </div>
                        </div>
                    @endif

                    <div style="padding-top: 0.5rem;">
                        <button type="button" wire:click="saveFundingFeeSettings" class="wallet-btn-primary">
                            Save Policy Changes
                        </button>
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
