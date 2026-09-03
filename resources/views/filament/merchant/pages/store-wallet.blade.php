<x-filament-panels::page>
    @php
        $store = \Filament\Facades\Filament::getTenant();
        $mainWallet = $store?->mainWallet();
        $profitWallet = $store?->profitWallet();
        $balance = $mainWallet?->balance ?? 0.00;
        $profitBalance = $profitWallet?->balance ?? 0.00;
        $virtualAccount = \App\Models\VirtualAccount::where('holder_type', get_class($store))->where('holder_id', $store?->id)->first();
        $transactions = $mainWallet?->transactions()->latest()->take(10)->get() ?? collect();
    @endphp

    <div style="font-family: inherit; display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Wallet Overview Section -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            <!-- Main Wallet Balance Card (Filament Primary Theme) -->
            <div style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); border-radius: 20px; padding: 1.75rem; color: white; box-shadow: 0 10px 25px -5px rgba(217, 119, 6, 0.35); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 170px;">
                <div style="position: absolute; right: -20px; bottom: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.12); border-radius: 50%; pointer-events: none;"></div>
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; tracking: 0.1em; opacity: 0.85;">Store Main Balance</span>
                    <h2 style="font-size: 2.25rem; font-weight: 900; margin-top: 0.5rem; margin-bottom: 0; tracking: -0.02em;">₦{{ number_format($balance, 2) }}</h2>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; border-top: 1px solid rgba(255,255,255,0.25); padding-top: 0.875rem; margin-top: 1rem;">
                    <span style="opacity: 0.9;">Operating Balance</span>
                    <span style="background: rgba(255,255,255,0.25); padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 800; font-size: 0.7rem; letter-spacing: 0.05em;">MAIN WALLET</span>
                </div>
            </div>

            <!-- Profit Wallet Balance Card -->
            <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 20px; padding: 1.75rem; color: white; box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.35); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 170px;">
                <div style="position: absolute; right: -20px; bottom: -20px; width: 120px; height: 120px; background: rgba(255,255,255,0.12); border-radius: 50%; pointer-events: none;"></div>
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; tracking: 0.1em; opacity: 0.85;">Store Profit Margin</span>
                    <h2 style="font-size: 2.25rem; font-weight: 900; margin-top: 0.5rem; margin-bottom: 0; tracking: -0.02em;">₦{{ number_format($profitBalance, 2) }}</h2>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; border-top: 1px solid rgba(255,255,255,0.25); padding-top: 0.875rem; margin-top: 1rem;">
                    <span style="opacity: 0.9;">Earned Profit</span>
                    <span style="background: rgba(255,255,255,0.25); padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 800; font-size: 0.7rem; letter-spacing: 0.05em;">PROFIT WALLET</span>
                </div>
            </div>

            <!-- Account Details Card -->
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; justify-content: space-between; min-height: 170px;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #6b7280;">Store Account Holder</span>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #111827; margin-top: 0.25rem; margin-bottom: 0.25rem;">{{ $store?->name ?? 'Merchant Store' }}</h3>
                    <p style="font-size: 0.75rem; color: #9ca3af; margin: 0;">Store ID: <span style="font-family: monospace; font-weight: 700; color: #4b5563;">{{ $store?->id }}</span></p>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; border-top: 1px solid #f3f4f6; padding-top: 0.875rem; margin-top: 1rem;">
                    <span style="color: #6b7280; font-weight: 600;">Payment Provider</span>
                    <span style="color: #d97706; font-weight: 800;">PayMint Infrastructure</span>
                </div>
            </div>
        </div>

        <!-- Dedicated Virtual Bank Account Card -->
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Dedicated Virtual Bank Account</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Transfer funds to your store virtual account to credit your store main balance instantly 24/7.</p>
            </div>

            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

            @if($virtualAccount)
                <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; align-items: center;">
                    <div>
                        <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #9ca3af; display: block; margin-bottom: 0.25rem;">Bank Name</span>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="width: 10px; height: 10px; background-color: #10b981; border-radius: 50%; display: inline-block;"></span>
                            <span style="font-size: 1rem; font-weight: 800; color: #111827;">{{ $virtualAccount->bank_name }}</span>
                        </div>
                    </div>

                    <div>
                        <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #9ca3af; display: block; margin-bottom: 0.25rem;">Account Number</span>
                        <span style="font-size: 1.5rem; font-family: monospace; font-weight: 900; color: #d97706; tracking: 0.05em;">
                            {{ $virtualAccount->account_number }}
                        </span>
                    </div>

                    <div>
                        <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #9ca3af; display: block; margin-bottom: 0.25rem;">Account Name</span>
                        <span style="font-size: 0.875rem; font-weight: 800; color: #111827; word-break: break-all;">
                            {{ $virtualAccount->account_name }}
                        </span>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="generateVirtualAccount" style="display: flex; flex-direction: column; gap: 1.25rem; max-width: 540px;">
                    <div>
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151; display: block; margin-bottom: 0.5rem;">Verification Document Type</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            <label style="border: 2px solid {{ $kycType === 'nin' ? '#d97706' : '#e5e7eb' }}; background-color: {{ $kycType === 'nin' ? '#fffbebf' : 'white' }}; padding: 0.75rem 1rem; border-radius: 12px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s;">
                                <span style="font-size: 0.875rem; font-weight: 700; color: {{ $kycType === 'nin' ? '#d97706' : '#374151' }};">NIN (National ID)</span>
                                <input type="radio" wire:model.live="kycType" value="nin" style="accent-color: #d97706;">
                            </label>

                            <label style="border: 2px solid {{ $kycType === 'bvn' ? '#d97706' : '#e5e7eb' }}; background-color: {{ $kycType === 'bvn' ? '#fffbebf' : 'white' }}; padding: 0.75rem 1rem; border-radius: 12px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s;">
                                <span style="font-size: 0.875rem; font-weight: 700; color: {{ $kycType === 'bvn' ? '#d97706' : '#374151' }};">BVN (Bank Verif. No)</span>
                                <input type="radio" wire:model.live="kycType" value="bvn" style="accent-color: #d97706;">
                            </label>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">11-Digit {{ strtoupper($kycType) }} Number</label>
                        <input 
                            type="text" 
                            wire:model="kycNumber" 
                            placeholder="e.g. 22123456789" 
                            maxlength="11"
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none; transition: border-color 0.2s;"
                        />
                        @error('kycNumber') <span style="font-size: 0.75rem; color: #dc2626;">{{ $message }}</span> @enderror
                    </div>

                    @if(empty($store?->owner?->phone))
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Owner Phone Number</label>
                            <input 
                                type="text" 
                                wire:model="phone" 
                                placeholder="e.g. 08012345678" 
                                maxlength="11"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none; transition: border-color 0.2s;"
                            />
                            @error('phone') <span style="font-size: 0.75rem; color: #dc2626;">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div>
                        <button type="submit" wire:loading.attr="disabled" style="background-color: #d97706; color: white; font-weight: 700; font-size: 0.875rem; padding: 0.75rem 1.75rem; border: none; border-radius: 12px; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.25);">
                            <span wire:loading.remove wire:target="generateVirtualAccount">Generate Store Bank Account</span>
                            <span wire:loading wire:target="generateVirtualAccount">Generating Account...</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Recent Store Wallet Transactions Section -->
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Recent Wallet Activity</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Audit log of store funding deposits and service debits.</p>
            </div>

            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

            @if($transactions->isEmpty())
                <div style="text-align: center; padding: 2.5rem 1rem; color: #9ca3af; font-size: 0.875rem;">
                    No transactions logged yet. Transfer funds to your store virtual account to see history.
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid #e5e7eb; font-size: 0.75rem; text-transform: uppercase; color: #9ca3af;">
                                <th style="padding: 0.75rem 1rem;">Type</th>
                                <th style="padding: 0.75rem 1rem;">Amount</th>
                                <th style="padding: 0.75rem 1rem;">Reference</th>
                                <th style="padding: 0.75rem 1rem;">Description</th>
                                <th style="padding: 0.75rem 1rem;">Status</th>
                                <th style="padding: 0.75rem 1rem;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 1rem; font-weight: 800; color: {{ $tx->type === 'credit' ? '#059669' : '#dc2626' }}; text-transform: uppercase;">
                                        {{ $tx->type }}
                                    </td>
                                    <td style="padding: 1rem; font-weight: 800; color: #111827;">
                                        ₦{{ number_format($tx->amount, 2) }}
                                    </td>
                                    <td style="padding: 1rem; font-family: monospace; font-size: 0.8rem; color: #4b5563;">
                                        {{ $tx->reference }}
                                    </td>
                                    <td style="padding: 1rem; color: #374151;">
                                        {{ $tx->description }}
                                    </td>
                                    <td style="padding: 1rem;">
                                        <span style="background-color: #d1fae5; color: #065f46; font-size: 0.7rem; font-weight: 800; padding: 0.25rem 0.5rem; border-radius: 6px; text-transform: uppercase;">
                                            {{ $tx->status }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; color: #9ca3af; font-size: 0.8rem;">
                                        {{ $tx->created_at->format('M d, Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</x-filament-panels::page>
