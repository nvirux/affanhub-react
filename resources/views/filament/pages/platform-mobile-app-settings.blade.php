<x-filament-panels::page>
    <form wire:submit.prevent="save" style="display: flex; flex-direction: column; gap: 1.5rem; max-width: 760px;">
        
        <!-- 1. Master Builder Control Card -->
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">App Builder Service Availability</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">
                        Master switch to turn the mobile app creation feature on or off across all stores.
                    </p>
                </div>
                <label style="position: relative; display: inline-flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" wire:model.live="builderEnabled" style="sr-only: true; width: 2.75rem; height: 1.5rem; accent-color: #10b981; cursor: pointer;">
                    <span style="margin-left: 0.75rem; font-size: 0.875rem; font-weight: 800; color: {{ $builderEnabled ? '#059669' : '#dc2626' }};">
                        {{ $builderEnabled ? 'ACTIVE / OPEN' : 'PAUSED / OFF' }}
                    </span>
                </label>
            </div>
            @if(! $builderEnabled)
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.8125rem; color: #991b1b; display: flex; align-items: center; gap: 0.5rem;">
                    <span>⚠️</span>
                    <span>When paused, merchants cannot order or compile new mobile apps. Existing compiled APK/AAB files will still remain downloadable.</span>
                </div>
            @endif
        </div>

        <!-- 2. Store Requirements & Access Restrictions Card -->
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.25rem;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Eligibility & Store Requirements</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">
                        Specify which stores and plans are allowed to request a mobile app.
                    </p>
                </div>
                <div style="font-size: 1.75rem;">🛡️</div>
            </div>

            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <!-- Custom Domain Requirement -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                            <label style="font-size: 0.875rem; font-weight: 800; color: #1e293b; margin: 0;">Require Custom Domain</label>
                            <input type="checkbox" wire:model="requireCustomDomain" style="width: 1.25rem; height: 1.25rem; accent-color: #10b981; cursor: pointer;">
                        </div>
                        <p style="font-size: 0.75rem; color: #64748b; line-height: 1.4; margin: 0;">
                            When enabled, only stores that have mapped and verified their own custom domain (e.g. <code>store.com</code>) can create an app.
                        </p>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 700; margin-top: 0.75rem; color: {{ $requireCustomDomain ? '#2563eb' : '#64748b' }};">
                        {{ $requireCustomDomain ? '✓ Custom domain mandatory' : '○ Allows subdomains (affanhub.com)' }}
                    </span>
                </div>

                <!-- Allowed Subscription Plans -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <label style="font-size: 0.875rem; font-weight: 800; color: #1e293b; margin: 0; display: block; margin-bottom: 0.5rem;">Minimum Subscription Plan</label>
                        <p style="font-size: 0.75rem; color: #64748b; line-height: 1.4; margin-bottom: 0.75rem;">
                            Restrict app creation to higher tier plans or allow all active stores.
                        </p>
                    </div>
                    <select 
                        wire:model="allowedPlans" 
                        style="width: 100%; padding: 0.65rem 0.85rem; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.875rem; font-weight: 700; color: #0f172a; background: white;"
                    >
                        <option value="all">All Plans (Starter, Pro, Enterprise)</option>
                        <option value="pro_and_above">Pro & Enterprise Plans Only</option>
                        <option value="enterprise_only">Enterprise Plan Only</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. Pricing Configuration Card -->
        <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 20px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
            
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Merchant Mobile App Pricing Engine</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">
                        Control how much merchants pay for their branded Android APK and Google Play Store submission.
                    </p>
                </div>
                <div style="font-size: 2rem;">💳</div>
            </div>

            <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

            <!-- Fee Inputs -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                
                <!-- 1. Android App Setup & APK Build Fee -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <span style="font-size: 1.2rem;">⚡</span>
                        <label style="font-size: 0.875rem; font-weight: 800; color: #1e293b; margin: 0;">Base Android App Fee</label>
                    </div>
                    <p style="font-size: 0.75rem; color: #64748b; line-height: 1.4; margin-bottom: 1rem;">
                        Fee charged to compile the merchant's branded APK, configure native icons, and provide permanent Cloudflare R2 download hosting.
                    </p>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #64748b;">₦</span>
                        <input 
                            type="number" 
                            step="100" 
                            min="0" 
                            wire:model="setupFee" 
                            style="width: 100%; padding: 0.75rem 0.85rem 0.75rem 2.2rem; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 1.1rem; font-weight: 800; color: #0f172a;"
                        >
                    </div>
                    <span style="font-size: 0.7rem; color: #10b981; font-weight: 700; display: block; margin-top: 0.35rem;">Default: ₦15,000</span>
                </div>

                <!-- 2. Google Play Store Submission Fee -->
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px; padding: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <span style="font-size: 1.2rem;">🚀</span>
                        <label style="font-size: 0.875rem; font-weight: 800; color: #14532d; margin: 0;">Play Store Publishing Fee</label>
                    </div>
                    <p style="font-size: 0.75rem; color: #15803d; line-height: 1.4; margin-bottom: 1rem;">
                        Optional add-on fee charged when the merchant requests official packaging, signing, and submission to the Google Play Store.
                    </p>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #15803d;">₦</span>
                        <input 
                            type="number" 
                            step="500" 
                            min="0" 
                            wire:model="playstoreFee" 
                            style="width: 100%; padding: 0.75rem 0.85rem 0.75rem 2.2rem; border-radius: 10px; border: 1.5px solid #86efac; font-size: 1.1rem; font-weight: 800; color: #14532d;"
                        >
                    </div>
                    <span style="font-size: 0.7rem; color: #15803d; font-weight: 700; display: block; margin-top: 0.35rem;">Default: ₦30,000</span>
                </div>

            </div>

            <!-- Example Preview -->
            <div style="background-color: #f1f5f9; border-radius: 12px; padding: 1rem; border-left: 4px solid #3b82f6;">
                <span style="font-size: 0.8125rem; font-weight: 700; color: #1e293b; display: block; margin-bottom: 0.25rem;">Live Pricing Calculation on Merchant Dashboards:</span>
                <div style="font-size: 0.75rem; color: #475569; display: flex; flex-direction: column; gap: 0.2rem;">
                    <div>• <strong>APK Only Build:</strong> ₦{{ number_format($setupFee, 2) }}</div>
                    <div>• <strong>APK + Google Play Publishing:</strong> ₦{{ number_format($setupFee + $playstoreFee, 2) }} (Bundle Deal)</div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button 
                    type="submit" 
                    style="background: #10b981; color: white; padding: 0.75rem 1.75rem; border-radius: 10px; font-weight: 800; font-size: 0.875rem; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);"
                >
                    <span wire:loading.remove>Save All Settings</span>
                    <span wire:loading>Saving...</span>
                </button>
            </div>

        </div>

    </form>
</x-filament-panels::page>
