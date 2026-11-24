<x-filament-panels::page>
    <form wire:submit.prevent="save" style="display: flex; flex-direction: column; gap: 1.5rem; max-width: 900px;">
        
        <!-- Header Banner / Explanation -->
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 20px; padding: 1.75rem 2rem; color: white; box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background-color: rgba(255, 255, 255, 0.2);">
                    <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </span>
                <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0; color: white;">Guaranteed Profit Floor & Margin Protection</h2>
            </div>
            <p style="font-size: 0.875rem; color: #d1fae5; margin: 0; line-height: 1.5; max-width: 700px;">
                Never sell at a loss or at ₦0 profit again. Even if a data plan has no custom tier prices configured, or if an upstream provider increases their cost, the system automatically clamps and guarantees minimum profit per transaction.
            </p>
        </div>

        <!-- Section 1: Tier Data Minimum Profit Floors -->
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">1. Data Plans Minimum Profit per Tier (₦)</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">
                    Minimum profit added on top of the provider cost price (<code style="background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px;">Cost + Margin = Floor Wholesale Price</code>).
                </p>
            </div>

            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
                <!-- Starter Tier -->
                <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.875rem; font-weight: 800; color: #111827;">Starter Tier Stores</span>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #059669; background-color: #ecfdf5; padding: 2px 8px; border-radius: 20px;">Free / Default</span>
                    </div>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">Base wholesale profit from free & non-subscribed stores.</p>
                    <div style="position: relative; margin-top: 0.25rem;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">₦</span>
                        <input 
                            type="number" 
                            step="1" 
                            min="0" 
                            wire:model.live="starterMargin" 
                            style="width: 100%; padding: 0.65rem 0.85rem; padding-left: 2rem; border-radius: 10px; border: 1px solid #d1d5db; font-size: 0.95rem; font-weight: 800; color: #111827;"
                        >
                    </div>
                </div>

                <!-- Pro Tier -->
                <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.875rem; font-weight: 800; color: #1e40af;">Pro Tier Stores</span>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #2563eb; background-color: #dbeafe; padding: 2px 8px; border-radius: 20px;">Subscribed</span>
                    </div>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">Competitive rate given to paying Pro merchants.</p>
                    <div style="position: relative; margin-top: 0.25rem;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">₦</span>
                        <input 
                            type="number" 
                            step="1" 
                            min="0" 
                            wire:model.live="proMargin" 
                            style="width: 100%; padding: 0.65rem 0.85rem; padding-left: 2rem; border-radius: 10px; border: 1px solid #93c5fd; font-size: 0.95rem; font-weight: 800; color: #1e3a8a;"
                        >
                    </div>
                </div>

                <!-- Enterprise Tier -->
                <div style="background-color: #faf5ff; border: 1px solid #e9d5ff; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.875rem; font-weight: 800; color: #6b21a8;">Enterprise Tier Stores</span>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #7e22ce; background-color: #f3e8ff; padding: 2px 8px; border-radius: 20px;">Top VIP Tier</span>
                    </div>
                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">High volume VIP wholesale rate floor.</p>
                    <div style="position: relative; margin-top: 0.25rem;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">₦</span>
                        <input 
                            type="number" 
                            step="1" 
                            min="0" 
                            wire:model.live="enterpriseMargin" 
                            style="width: 100%; padding: 0.65rem 0.85rem; padding-left: 2rem; border-radius: 10px; border: 1px solid #d8b4fe; font-size: 0.95rem; font-weight: 800; color: #581c87;"
                        >
                    </div>
                </div>
            </div>

            <!-- Absolute Bottom Safety Floor -->
            <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 0.875rem; font-weight: 800; color: #92400e; display: block;">Absolute System Floor (₦)</span>
                        <span style="font-size: 0.75rem; color: #b45309;">Hard bottom ceiling: No tier or promotional override can ever fall below this profit margin.</span>
                    </div>
                    <div style="position: relative; width: 140px;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">₦</span>
                        <input 
                            type="number" 
                            step="1" 
                            min="0" 
                            wire:model.live="absoluteFloor" 
                            style="width: 100%; padding: 0.5rem 0.75rem; padding-left: 2rem; border-radius: 10px; border: 1px solid #fcd34d; font-size: 0.875rem; font-weight: 800; color: #92400e;"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Store Owner & Customer Margin Protection -->
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">2. Store Owner (Merchant) Margin Protection</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">
                    Guarantees that store owners always earn profit from customer sales and never sell below wholesale price.
                </p>
            </div>

            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 0.875rem; font-weight: 800; color: #111827; display: block;">Minimum Merchant Retail Spread (₦)</span>
                        <span style="font-size: 0.75rem; color: #6b7280;">If a store hasn't customized their customer retail price, the customer price will be at least (Wholesale Cost + this amount).</span>
                    </div>
                    <div style="position: relative; width: 140px;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">₦</span>
                        <input 
                            type="number" 
                            step="1" 
                            min="0" 
                            wire:model.live="merchantMargin" 
                            style="width: 100%; padding: 0.5rem 0.75rem; padding-left: 2rem; border-radius: 10px; border: 1px solid #d1d5db; font-size: 0.875rem; font-weight: 800; color: #111827;"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Airtime Profit Margin -->
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">3. Airtime Minimum Profit Margin (%)</h3>
                <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">
                    Percentage retained by the platform from telecom discount on airtime recharges.
                </p>
            </div>

            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 0.875rem; font-weight: 800; color: #111827; display: block;">Airtime Platform Profit Retained (%)</span>
                    <span style="font-size: 0.75rem; color: #6b7280;">Example: If provider gives 3.0% discount and platform retains 0.5%, store wholesale discount is capped at 2.5%.</span>
                </div>
                <div style="position: relative; width: 140px;">
                    <input 
                        type="number" 
                        step="0.1" 
                        min="0" 
                        max="10" 
                        wire:model.live="airtimeMarginPercent" 
                        style="width: 100%; padding: 0.5rem 0.75rem; padding-right: 2rem; border-radius: 10px; border: 1px solid #d1d5db; font-size: 0.875rem; font-weight: 800; color: #111827;"
                    >
                    <span style="position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #9ca3af;">%</span>
                </div>
            </div>
        </div>

        <!-- Section 4: Live Interactive Margin Simulator -->
        @php
            $cost = max(0, (float) $simCost);
            $starterWholesale = $cost + max((float) $starterMargin, (float) $absoluteFloor);
            $proWholesale = $cost + max((float) $proMargin, (float) $absoluteFloor);
            $entWholesale = $cost + max((float) $enterpriseMargin, (float) $absoluteFloor);

            $starterRetail = $starterWholesale + (float) $merchantMargin;
            $proRetail = $proWholesale + (float) $merchantMargin;
            $entRetail = $entWholesale + (float) $merchantMargin;

            // Airtime simulation
            $airtimeFace = max(0, (float) $simAirtimeAmount);
            $vendorDisc = (float) $simAirtimeVendorDiscount;
            $airtimePlatMargin = (float) $airtimeMarginPercent;
            $maxStoreDisc = max(0, $vendorDisc - $airtimePlatMargin);
            $airtimePlatformProfit = round($airtimeFace * ($airtimePlatMargin / 100), 2);
            $airtimeWholesaleCost = round($airtimeFace * (1 - ($maxStoreDisc / 100)), 2);
        @endphp

        <div style="background-color: #0f172a; border-radius: 20px; padding: 2rem; color: white; display: flex; flex-direction: column; gap: 1.5rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: white; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #10b981;"></span>
                        Live Interactive Pricing Simulator
                    </h3>
                    <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.25rem; margin-bottom: 0;">
                        Type sample costs below to immediately preview exact wholesale prices and platform profits.
                    </p>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <label style="font-size: 0.75rem; font-weight: 700; color: #cbd5e1;">Test Provider Cost:</label>
                    <div style="position: relative; width: 140px;">
                        <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #64748b;">₦</span>
                        <input 
                            type="number" 
                            step="1" 
                            wire:model.live="simCost" 
                            style="width: 100%; padding: 0.4rem 0.6rem; padding-left: 1.75rem; border-radius: 8px; border: 1px solid #334155; background-color: #1e293b; color: white; font-size: 0.875rem; font-weight: 800;"
                        >
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                <!-- Starter Sim -->
                <div style="background-color: #1e293b; border: 1px solid #334155; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.8rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Starter Tier</span>
                        <span style="font-size: 0.75rem; font-weight: 800; color: #34d399; background-color: rgba(16, 185, 129, 0.15); padding: 2px 6px; border-radius: 6px;">+₦{{ number_format($starterWholesale - $cost, 2) }} Profit</span>
                    </div>
                    <div style="margin-top: 0.5rem;">
                        <span style="font-size: 0.7rem; color: #64748b; display: block;">Store Wholesale Cost</span>
                        <span style="font-size: 1.4rem; font-weight: 900; color: white;">₦{{ number_format($starterWholesale, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #94a3b8; border-top: 1px solid #334155; padding-top: 0.5rem; margin-top: 0.25rem;">
                        <span>Floor Customer Retail:</span>
                        <span style="font-weight: 700; color: white;">₦{{ number_format($starterRetail, 2) }}</span>
                    </div>
                </div>

                <!-- Pro Sim -->
                <div style="background-color: #1e293b; border: 1px solid #1e3a8a; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.8rem; font-weight: 800; color: #60a5fa; text-transform: uppercase;">Pro Tier</span>
                        <span style="font-size: 0.75rem; font-weight: 800; color: #60a5fa; background-color: rgba(37, 99, 235, 0.15); padding: 2px 6px; border-radius: 6px;">+₦{{ number_format($proWholesale - $cost, 2) }} Profit</span>
                    </div>
                    <div style="margin-top: 0.5rem;">
                        <span style="font-size: 0.7rem; color: #64748b; display: block;">Store Wholesale Cost</span>
                        <span style="font-size: 1.4rem; font-weight: 900; color: white;">₦{{ number_format($proWholesale, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #94a3b8; border-top: 1px solid #334155; padding-top: 0.5rem; margin-top: 0.25rem;">
                        <span>Floor Customer Retail:</span>
                        <span style="font-weight: 700; color: white;">₦{{ number_format($proRetail, 2) }}</span>
                    </div>
                </div>

                <!-- Enterprise Sim -->
                <div style="background-color: #1e293b; border: 1px solid #581c87; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.8rem; font-weight: 800; color: #c084fc; text-transform: uppercase;">Enterprise Tier</span>
                        <span style="font-size: 0.75rem; font-weight: 800; color: #c084fc; background-color: rgba(168, 85, 247, 0.15); padding: 2px 6px; border-radius: 6px;">+₦{{ number_format($entWholesale - $cost, 2) }} Profit</span>
                    </div>
                    <div style="margin-top: 0.5rem;">
                        <span style="font-size: 0.7rem; color: #64748b; display: block;">Store Wholesale Cost</span>
                        <span style="font-size: 1.4rem; font-weight: 900; color: white;">₦{{ number_format($entWholesale, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #94a3b8; border-top: 1px solid #334155; padding-top: 0.5rem; margin-top: 0.25rem;">
                        <span>Floor Customer Retail:</span>
                        <span style="font-weight: 700; color: white;">₦{{ number_format($entRetail, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Airtime Preview Strip -->
            <div style="background-color: rgba(255, 255, 255, 0.04); border: 1px dashed #334155; border-radius: 12px; padding: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; font-size: 0.8rem;">
                <div style="color: #94a3b8;">
                    Airtime Simulation (₦{{ number_format($airtimeFace, 0) }} @ {{ $vendorDisc }}% vendor discount):
                </div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <span style="color: #cbd5e1;">Platform Retains: <strong style="color: #34d399;">{{ $airtimePlatMargin }}% (+₦{{ number_format($airtimePlatformProfit, 2) }})</strong></span>
                    <span style="color: #cbd5e1;">Max Store Discount: <strong style="color: #60a5fa;">{{ $maxStoreDisc }}% (Cost: ₦{{ number_format($airtimeWholesaleCost, 2) }})</strong></span>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 0.5rem;">
            <button 
                type="submit" 
                style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; font-weight: 800; font-size: 0.95rem; padding: 0.85rem 2rem; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.4); display: flex; align-items: center; gap: 0.5rem;"
            >
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Guaranteed Profit Settings
            </button>
        </div>
    </form>
</x-filament-panels::page>

