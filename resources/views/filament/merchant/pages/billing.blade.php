<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $activeSub = $tenant->activeSubscription;
        $currentPlan = $activeSub ? $activeSub->plan : \App\Models\Plan::where('slug', 'starter')->first();
        // Custom Plan Order: Pro first, then Enterprise, then Starter
        $plans = \App\Models\Plan::all()->sortBy(function($plan) {
            return match($plan->slug) {
                'pro' => 1,
                'enterprise' => 2,
                'starter' => 3,
                default => 4
            };
        });
    @endphp

    <div style="font-family: inherit; display: flex; flex-direction: column; gap: 2rem;">
        
        {{-- Top Section: Current Status & Entitlements --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            
            {{-- Current Plan Info Card --}}
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                <div style="position: absolute; right: -20px; top: -20px; width: 120px; height: 120px; border-radius: 50%; background-color: rgba(245, 158, 11, 0.08); filter: blur(20px);"></div>
                
                <div>
                    <h3 style="font-size: 0.75rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Active Subscription</h3>
                    
                    <div style="margin-top: 1rem; display: flex; align-items: baseline; gap: 0.5rem;">
                        <span style="font-size: 2rem; font-weight: 800; color: #111111; letter-spacing: -0.02em;">
                            {{ $currentPlan?->name ?? 'Starter' }}
                        </span>
                        @if($activeSub)
                            <span style="font-size: 0.875rem; font-weight: 500; color: #666666;">
                                / {{ $activeSub->billing_interval === 'year' ? 'yearly' : 'monthly' }}
                            </span>
                        @endif
                    </div>
                </div>

                <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem;">
                        <span style="color: #666666;">Status</span>
                        <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; color: #065f46; background-color: #ecfdf5;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #10b981;"></span>
                            {{ $activeSub ? ucfirst($activeSub->status) : 'Active (Free)' }}
                        </span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem;">
                        <span style="color: #666666;">Price</span>
                        <span style="font-weight: 700; color: #111111;">
                            {{ $activeSub ? '₦' . number_format($activeSub->price, 2) : 'Free' }}
                        </span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem;">
                        <span style="color: #666666;">Renews On</span>
                        <span style="font-weight: 700; color: #111111;">
                            {{ $activeSub?->ends_at ? $activeSub->ends_at->format('M d, Y') : 'Never (Lifetime)' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Entitlements Usage & Capability Cards --}}
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1rem;">
                <h3 style="font-size: 0.75rem; font-weight: 700; color: #888888; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; margin-bottom: 0.5rem;">Plan Capability & Entitlements</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    {{-- Staff Account Limit --}}
                    @php
                        $staffLimit = $tenant->getFeatureLimit('staff_limit');
                        $isUnlimitedStaff = $staffLimit >= 999;
                    @endphp
                    <div style="padding: 1rem; border-radius: 12px; background-color: #f9fafb; border: 1px solid #f3f4f6; display: flex; flex-direction: column; justify-content: space-between;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8125rem; font-weight: 600; color: #374151;">
                            <span>Staff Account Limit</span>
                            <span style="color: #111827;">{{ $isUnlimitedStaff ? 'Unlimited' : $staffLimit }}</span>
                        </div>
                        <div style="margin-top: 0.5rem; width: 100%; background-color: #e5e7eb; border-radius: 9999px; height: 6px; overflow: hidden;">
                            <div style="background-color: #f59e0b; height: 100%; border-radius: 9999px; width: {{ $isUnlimitedStaff ? '100' : '20' }}%;"></div>
                        </div>
                    </div>

                    {{-- Custom Domains --}}
                    @php $hasCustomDomain = $tenant->hasFeature('custom_domain'); @endphp
                    <div style="padding: 1rem; border-radius: 12px; background-color: #f9fafb; border: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Custom Domains</span>
                            <span style="font-size: 0.6875rem; color: #9ca3af;">Map custom domain</span>
                        </div>
                        <span style="padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; {{ $hasCustomDomain ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #f3f4f6; color: #4b5563;' }}">
                            {{ $hasCustomDomain ? 'Active' : 'Locked' }}
                        </span>
                    </div>

                    {{-- Custom Margin Settings --}}
                    @php $hasCustomMargins = $tenant->hasFeature('custom_pricing_margins'); @endphp
                    <div style="padding: 1rem; border-radius: 12px; background-color: #f9fafb; border: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Custom Margins</span>
                            <span style="font-size: 0.6875rem; color: #9ca3af;">Custom VTU markup</span>
                        </div>
                        <span style="padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; {{ $hasCustomMargins ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #f3f4f6; color: #4b5563;' }}">
                            {{ $hasCustomMargins ? 'Active' : 'Locked' }}
                        </span>
                    </div>

                    {{-- Developer API --}}
                    @php $hasApi = $tenant->hasFeature('api_access'); @endphp
                    <div style="padding: 1rem; border-radius: 12px; background-color: #f9fafb; border: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 0.8125rem; font-weight: 600; color: #374151;">Developer API</span>
                            <span style="font-size: 0.6875rem; color: #9ca3af;">Integrate API endpoints</span>
                        </div>
                        <span style="padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; {{ $hasApi ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #f3f4f6; color: #4b5563;' }}">
                            {{ $hasApi ? 'Active' : 'Locked' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Interval Toggle Header --}}
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-top: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0;">Upgrade or Switch Subscription Plans</h2>
            <p style="font-size: 0.875rem; color: #6b7280; margin: 0.5rem 0 1.5rem 0;">Unlock advanced multi-tenant features to scale your digital business</p>
            
            <div style="display: inline-flex; background-color: #f3f4f6; padding: 0.25rem; border-radius: 12px; align-items: center;">
                <button 
                    wire:click="selectInterval('month')" 
                    style="padding: 0.5rem 1.25rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.2s; {{ $interval === 'month' ? 'background-color: white; color: #d97706; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'background-color: transparent; color: #4b5563;' }}">
                    Monthly
                </button>
                <button 
                    wire:click="selectInterval('year')" 
                    style="padding: 0.5rem 1.25rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.2s; position: relative; {{ $interval === 'year' ? 'background-color: white; color: #d97706; box-shadow: 0 1px 3px rgba(0,0,0,0.1);' : 'background-color: transparent; color: #4b5563;' }}">
                    Yearly
                    <span style="position: absolute; top: -8px; right: -8px; background-color: #10b981; color: white; font-size: 9px; font-weight: 700; padding: 1px 6px; border-radius: 9999px;">-20%</span>
                </button>
            </div>
        </div>

        {{-- Plan Cards Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 1rem;">
            @foreach($plans as $plan)
                @php
                    $isCurrent = $activeSub 
                        ? ($activeSub->plan_id === $plan->id && $activeSub->billing_interval === $interval)
                        : ($plan->slug === 'starter' && $interval === 'month');

                    $hasPaidSub = $activeSub && in_array($activeSub->plan?->slug, ['pro', 'enterprise']);
                    $isDowngradeToStarter = $hasPaidSub && $plan->slug === 'starter';

                    $price = $interval === 'year' ? $plan->price_yearly : $plan->price_monthly;
                    $hasPrice = !is_null($price);
                @endphp
                <div style="background-color: white; border: 1px solid {{ $isCurrent ? '#f59e0b' : 'rgba(0, 0, 0, 0.08)' }}; border-radius: 16px; padding: 2rem; box-shadow: {{ $isCurrent ? '0 10px 25px -5px rgba(245, 158, 11, 0.15)' : '0 4px 12px rgba(0, 0, 0, 0.03)' }}; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.3s;">
                    @if($isCurrent)
                        <span style="position: absolute; top: -12px; right: 24px; background-color: #f59e0b; color: white; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 9999px;">
                            Active Plan
                        </span>
                    @endif

                    <div>
                        <h4 style="font-size: 1.25rem; font-weight: 800; color: #111827; margin: 0;">{{ $plan->name }}</h4>
                        <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem; margin-bottom: 1.5rem; min-height: 40px;">
                            {{ $plan->description }}
                        </p>
                        
                        <div style="display: flex; align-items: baseline; color: #111827; margin-bottom: 2rem;">
                            @if($hasPrice)
                                <span style="font-size: 2.25rem; font-weight: 800; tracking-tight: -0.02em;">
                                    ₦{{ number_format($price, 0) }}
                                </span>
                                <span style="margin-left: 0.25rem; font-size: 0.875rem; font-weight: 600; color: #6b7280;">
                                    / {{ $interval === 'year' ? 'yr' : 'mo' }}
                                </span>
                            @else
                                <span style="font-size: 2rem; font-weight: 800; tracking-tight: -0.02em;">
                                    Custom Pricing
                                </span>
                            @endif
                        </div>

                        {{-- Features checkmarks --}}
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                            @php
                                $staffLimitVal = $plan->getFeatureValue('staff_limit');
                                $isStaffUnlimited = $staffLimitVal >= 999;
                                $hasCustomDomainVal = filter_var($plan->getFeatureValue('custom_domain'), FILTER_VALIDATE_BOOLEAN);
                                $hasMarginsVal = filter_var($plan->getFeatureValue('custom_pricing_margins'), FILTER_VALIDATE_BOOLEAN);
                                $hasApiVal = filter_var($plan->getFeatureValue('api_access'), FILTER_VALIDATE_BOOLEAN);
                            @endphp
                            
                            <li style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; color: #4b5563;">
                                <span style="color: #f59e0b; font-weight: 800; font-size: 1.125rem;">✓</span>
                                <span>{{ $isStaffUnlimited ? 'Unlimited' : $staffLimitVal }} Staff Accounts</span>
                            </li>
                            <li style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; {{ $hasCustomDomainVal ? 'color: #4b5563;' : 'color: #9ca3af; text-decoration: line-through;' }}">
                                <span style="{{ $hasCustomDomainVal ? 'color: #f59e0b;' : 'color: #9ca3af;' }} font-weight: 800; font-size: 1.125rem;">✓</span>
                                <span>Custom Domain Mapping</span>
                            </li>
                            <li style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; {{ $hasMarginsVal ? 'color: #4b5563;' : 'color: #9ca3af; text-decoration: line-through;' }}">
                                <span style="{{ $hasMarginsVal ? 'color: #f59e0b;' : 'color: #9ca3af;' }} font-weight: 800; font-size: 1.125rem;">✓</span>
                                <span>Custom Markup Margins</span>
                            </li>
                            <li style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; {{ $hasApiVal ? 'color: #4b5563;' : 'color: #9ca3af; text-decoration: line-through;' }}">
                                <span style="{{ $hasApiVal ? 'color: #f59e0b;' : 'color: #9ca3af;' }} font-weight: 800; font-size: 1.125rem;">✓</span>
                                <span>Developer API Access</span>
                            </li>
                        </ul>
                    </div>

                    <div style="margin-top: 2rem;">
                        @if($isCurrent)
                            <button disabled style="width: 100%; border: none; background-color: #f3f4f6; color: #9ca3af; font-weight: 700; padding: 0.875rem 1rem; border-radius: 12px; cursor: not-allowed; text-align: center;">
                                Plan Active
                            </button>
                        @elseif($isDowngradeToStarter)
                            <button disabled style="width: 100%; border: none; background-color: #f3f4f6; color: #9ca3af; font-weight: 700; padding: 0.875rem 1rem; border-radius: 12px; cursor: not-allowed; text-align: center;">
                                Downgrade Disabled
                            </button>
                        @elseif($plan->slug === 'enterprise')
                            <button wire:click="subscribe({{ $plan->id }})" style="width: 100%; border: none; background-color: #111827; color: white; font-weight: 700; padding: 0.875rem 1rem; border-radius: 12px; cursor: pointer; transition: background-color 0.2s; text-align: center;">
                                Contact Sales
                            </button>
                        @else
                            <button wire:click="subscribe({{ $plan->id }})" style="width: 100%; border: none; background-color: #f59e0b; color: white; font-weight: 700; padding: 0.875rem 1rem; border-radius: 12px; cursor: pointer; transition: background-color 0.2s; text-align: center; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.15);">
                                Select {{ $plan->name }}
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
