<x-filament-panels::page>
    <form wire:submit.prevent="save" style="display: flex; flex-direction: column; gap: 1.5rem; max-width: 720px;">
        
        <!-- Main Configuration Card -->
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
            
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">PayMint Virtual Account Funding Fee</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">
                    Configure the platform base fee deducted when deposits land into stores via PayMint / PalmPay dedicated virtual accounts.
                </p>
            </div>

            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

            <!-- Fee Type Selector -->
            <div>
                <label style="font-size: 0.875rem; font-weight: 700; color: #374151; display: block; margin-bottom: 0.5rem;">Fee Calculation Mode</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <label style="border: 2px solid {{ $feeType === 'percentage' ? '#d97706' : '#e5e7eb' }}; background-color: {{ $feeType === 'percentage' ? '#fffbebf' : 'white' }}; padding: 0.875rem 1rem; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s;">
                        <div>
                            <span style="font-size: 0.875rem; font-weight: 800; color: {{ $feeType === 'percentage' ? '#d97706' : '#111827' }}; display: block;">Percentage (%)</span>
                            <span style="font-size: 0.75rem; color: #6b7280;">Standard gateway deduction (e.g. 1.0%)</span>
                        </div>
                        <input type="radio" wire:model.live="feeType" value="percentage" style="accent-color: #d97706;">
                    </label>

                    <label style="border: 2px solid {{ $feeType === 'flat' ? '#d97706' : '#e5e7eb' }}; background-color: {{ $feeType === 'flat' ? '#fffbebf' : 'white' }}; padding: 0.875rem 1rem; border-radius: 14px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s;">
                        <div>
                            <span style="font-size: 0.875rem; font-weight: 800; color: {{ $feeType === 'flat' ? '#d97706' : '#111827' }}; display: block;">Flat Fee (₦)</span>
                            <span style="font-size: 0.75rem; color: #6b7280;">Fixed amount per deposit</span>
                        </div>
                        <input type="radio" wire:model.live="feeType" value="flat" style="accent-color: #d97706;">
                    </label>
                </div>
            </div>

            <!-- Conditional Inputs based on Fee Type -->
            @if($feeType === 'percentage')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151; display: block; margin-bottom: 0.35rem;">Base Fee Rate (%)</label>
                        <div style="position: relative;">
                            <input 
                                type="number" 
                                step="0.1" 
                                min="0" 
                                max="10" 
                                wire:model="feePercent" 
                                style="width: 100%; padding: 0.65rem 0.85rem; padding-right: 2rem; border-radius: 10px; border: 1px solid #d1d5db; font-size: 0.875rem; font-weight: 700;"
                            >
                            <span style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">%</span>
                        </div>
                        <p style="font-size: 0.7rem; color: #9ca3af; margin-top: 0.25rem;">Default is 1.0% (matches PayMint)</p>
                    </div>

                    <div>
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151; display: block; margin-bottom: 0.35rem;">Max Fee Cap (₦)</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">₦</span>
                            <input 
                                type="number" 
                                step="1" 
                                min="0" 
                                wire:model="maxFeeCap" 
                                style="width: 100%; padding: 0.65rem 0.85rem; padding-left: 2rem; border-radius: 10px; border: 1px solid #d1d5db; font-size: 0.875rem; font-weight: 700;"
                            >
                        </div>
                        <p style="font-size: 0.7rem; color: #9ca3af; margin-top: 0.25rem;">Cap fee even on large transfers (e.g. ₦100)</p>
                    </div>
                </div>
            @else
                <div>
                    <label style="font-size: 0.875rem; font-weight: 700; color: #374151; display: block; margin-bottom: 0.35rem;">Fixed Flat Fee (₦)</label>
                    <div style="position: relative; max-width: 320px;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">₦</span>
                        <input 
                            type="number" 
                            step="1" 
                            min="0" 
                            wire:model="feeFlat" 
                            style="width: 100%; padding: 0.65rem 0.85rem; padding-left: 2rem; border-radius: 10px; border: 1px solid #d1d5db; font-size: 0.875rem; font-weight: 700;"
                        >
                    </div>
                    <p style="font-size: 0.7rem; color: #9ca3af; margin-top: 0.25rem;">Deducted per successful transfer</p>
                </div>
            @endif

            <!-- Admin Platform Markup -->
            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <div>
                    <span style="font-size: 0.875rem; font-weight: 800; color: #111827; display: block;">Platform Admin Markup (Optional)</span>
                    <span style="font-size: 0.75rem; color: #6b7280;">Additional fee retained as platform profit. Set to 0% to charge gateway cost only.</span>
                </div>
                <div style="max-width: 240px; position: relative;">
                    <input 
                        type="number" 
                        step="0.1" 
                        min="0" 
                        max="10" 
                        wire:model="adminMarkupPercent" 
                        style="width: 100%; padding: 0.65rem 0.85rem; padding-right: 2rem; border-radius: 10px; border: 1px solid #d1d5db; font-size: 0.875rem; font-weight: 700;"
                    >
                    <span style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">%</span>
                </div>
            </div>

            <!-- Live Calculation Example Box -->
            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px; padding: 1.25rem;">
                <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #166534; display: block; margin-bottom: 0.5rem;">Deposit Flow Preview (₦1,000 Transfer)</span>
                @php
                    $sampleAmount = 1000;
                    $sampleBaseFee = $feeType === 'percentage' 
                        ? min($maxFeeCap, ($sampleAmount * $feePercent) / 100)
                        : $feeFlat;
                    $sampleMarkup = ($sampleAmount * $adminMarkupPercent) / 100;
                    $sampleTotalPlatformFee = $sampleBaseFee + $sampleMarkup;
                    $sampleStoreNet = max(0, $sampleAmount - $sampleTotalPlatformFee);
                @endphp
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; font-size: 0.8rem;">
                    <div>
                        <span style="color: #6b7280; display: block;">Customer Transfers:</span>
                        <strong style="color: #111827; font-size: 0.95rem;">₦{{ number_format($sampleAmount, 2) }}</strong>
                    </div>
                    <div>
                        <span style="color: #6b7280; display: block;">Total Platform Fee:</span>
                        <strong style="color: #dc2626; font-size: 0.95rem;">-₦{{ number_format($sampleTotalPlatformFee, 2) }}</strong>
                    </div>
                    <div>
                        <span style="color: #6b7280; display: block;">Store Wholesale Gets:</span>
                        <strong style="color: #15803d; font-size: 0.95rem;">₦{{ number_format($sampleStoreNet, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div style="display: flex; justify-content: flex-end; padding-top: 0.5rem;">
                <button 
                    type="submit" 
                    style="background-color: #d97706; color: white; padding: 0.75rem 1.75rem; border-radius: 12px; font-weight: 800; font-size: 0.875rem; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);"
                >
                    Save Platform Settings
                </button>
            </div>
        </div>
    </form>
</x-filament-panels::page>
