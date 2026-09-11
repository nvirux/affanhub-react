<div style="min-height: 100vh; background-color: #f8fafc; color: #0f172a; padding: 1.25rem 0.875rem; width: 100%; max-width: 100vw; overflow-x: hidden; box-sizing: border-box; font-family: inherit;">
    <style>
        *, *::before, *::after {
            box-sizing: border-box !important;
        }
        .onboarding-wrapper {
            width: 100%;
            max-width: 840px;
            margin: 0 auto;
            box-sizing: border-box;
        }
        .pricing-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1.25rem;
            width: 100%;
            margin: 0 0 1.75rem 0;
            box-sizing: border-box;
        }
        @media (min-width: 768px) {
            .pricing-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.5rem;
            }
        }
        .pricing-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 1.5rem 1.15rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 4px 16px -2px rgba(0, 0, 0, 0.05);
            border: 1.5px solid #e2e8f0;
            width: 100%;
            box-sizing: border-box;
            min-width: 0;
        }
        @media (min-width: 640px) {
            .pricing-card {
                padding: 2rem 1.65rem;
            }
        }
        .pricing-card.featured {
            border: 2.5px solid #f59e0b;
            background: linear-gradient(180deg, #ffffff 0%, #fffdf7 100%);
            box-shadow: 0 14px 30px -4px rgba(245, 158, 11, 0.16);
        }
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            font-size: 0.875rem;
            color: #475569;
            margin-bottom: 0.7rem;
            line-height: 1.4;
            word-break: break-word;
        }
        .feature-check {
            width: 19px;
            height: 19px;
            border-radius: 50%;
            background-color: #fef3c7;
            color: #b45309;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.7rem;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .feature-check.blue {
            background-color: #eff6ff;
            color: #2563eb;
        }
        .bottom-starter-box {
            width: 100%;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.15rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            margin: 0 0 1.75rem 0;
            box-sizing: border-box;
        }
        @media (min-width: 640px) {
            .bottom-starter-box {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 1.25rem;
            }
        }
    </style>

    <div class="onboarding-wrapper">
        
        {{-- Top Right "Skip with Starter" --}}
        <div style="display: flex; justify-content: flex-end; margin-bottom: 0.75rem; width: 100%;">
            <button wire:click="continueStarter" type="button" 
                    style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.4rem 0.85rem; background: white; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.8rem; font-weight: 700; color: #475569; cursor: pointer;">
                <span>Skip with Starter Plan</span>
                <span>→</span>
            </button>
        </div>

        {{-- Hero Header --}}
        <div style="text-align: center; width: 100%; margin: 0 0 1.5rem 0;">
            <h1 style="font-size: clamp(1.5rem, 5vw, 2.25rem); font-weight: 900; letter-spacing: -0.03em; line-height: 1.2; margin: 0; word-break: break-word;">
                Select a Plan for <span style="color: #f59e0b;">{{ $tenant->name }}</span>
            </h1>

            <p style="font-size: clamp(0.85rem, 2.8vw, 0.975rem); color: #64748b; margin: 0.5rem auto 0 auto; line-height: 1.45; max-width: 540px; word-break: break-word;">
                Get lowest wholesale VTU prices, map your custom domain (.com, .ng), and enable WhatsApp customer support.
            </p>

            {{-- Billing Toggle (Monthly / Yearly) --}}
            <div style="display: inline-flex; align-items: center; background: #e2e8f0; padding: 0.25rem; border-radius: 14px; margin: 1.25rem auto 0 auto; box-shadow: inset 0 1px 3px rgba(0,0,0,0.06); max-width: 100%; box-sizing: border-box;">
                <button wire:click="selectInterval('month')" type="button" 
                        style="padding: 0.4rem 0.95rem; font-size: 0.825rem; font-weight: 700; border-radius: 10px; border: none; cursor: pointer; transition: all 0.2s; {{ $interval === 'month' ? 'background: white; color: #0f172a; box-shadow: 0 2px 6px rgba(0,0,0,0.08);' : 'background: transparent; color: #64748b;' }}">
                    Monthly Billing
                </button>
                <button wire:click="selectInterval('year')" type="button" 
                        style="padding: 0.4rem 0.95rem; font-size: 0.825rem; font-weight: 700; border-radius: 10px; border: none; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.35rem; {{ $interval === 'year' ? 'background: white; color: #0f172a; box-shadow: 0 2px 6px rgba(0,0,0,0.08);' : 'background: transparent; color: #64748b;' }}">
                    <span>Yearly</span>
                    <span style="background: #ecfdf5; color: #059669; font-size: 0.675rem; padding: 0.1rem 0.35rem; border-radius: 9999px; font-weight: 800;">Save 15%</span>
                </button>
            </div>
        </div>

        {{-- 2-Plan Upgrade Grid (Pro & Enterprise) --}}
        <div class="pricing-grid">
            
            {{-- 1. Pro Plan (Spotlight / Featured) --}}
            <div class="pricing-card featured">
                <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; font-size: 0.675rem; font-weight: 900; padding: 0.25rem 0.85rem; border-radius: 9999px; letter-spacing: 0.05em; text-transform: uppercase; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.35); white-space: nowrap;">
                    ★ Most Popular
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin: 0.35rem 0 0.35rem 0;">
                        <h3 style="font-size: 1.3rem; font-weight: 900; color: #0f172a; margin: 0;">Pro</h3>
                        <span style="background: #fef3c7; color: #b45309; font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 9999px;">Recommended</span>
                    </div>
                    
                    <p style="font-size: 0.825rem; color: #64748b; margin: 0 0 1.15rem 0;">Everything you need to run a high-earning, professional VTU store.</p>
                    
                    <div style="margin: 0 0 1.15rem 0;">
                        <span style="font-size: clamp(2rem, 5vw, 2.5rem); font-weight: 900; color: #0f172a; letter-spacing: -0.03em;">
                            {{ $interval === 'year' ? '₦50,000' : '₦5,000' }}
                        </span>
                        <span style="color: #64748b; font-size: 0.825rem; font-weight: 600;">
                            / {{ $interval === 'year' ? 'year' : 'month' }}
                        </span>
                    </div>

                    <div style="border-top: 1px solid #fed7aa; padding-top: 1rem; margin-bottom: 1.25rem;">
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <strong style="color: #0f172a;">Custom Domain Mapping (.com, .ng)</strong>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <strong style="color: #0f172a;">Lowest Tier Wholesale Data Rates</strong>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>Customer WhatsApp Live Chat Widget</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>Full Brand Colors & Logo Uploads</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>10 Staff Accounts with Custom Roles</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>Customer Referral & Cash-back Engine</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>Priority Server Dispatch Queue & VIP Support</span>
                        </div>
                    </div>
                </div>

                @if($proPlan)
                    <button wire:click="payWithCheckout({{ $proPlan->id }})" type="button" 
                            style="width: 100%; min-height: 46px; border: none; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; font-weight: 900; font-size: 0.95rem; border-radius: 12px; cursor: pointer; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4); display: flex; align-items: center; justify-content: center; gap: 0.4rem; padding: 0.75rem 0.5rem; word-break: break-word;">
                        <span>Pay with PayMint Checkout</span>
                        <span>⚡</span>
                    </button>
                @endif
            </div>

            {{-- 2. Enterprise Plan --}}
            <div class="pricing-card">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin: 0 0 0.35rem 0;">
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0;">Enterprise</h3>
                        <span style="background: #eff6ff; color: #2563eb; font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 9999px;">High Volume</span>
                    </div>
                    
                    <p style="font-size: 0.825rem; color: #64748b; margin: 0 0 1.15rem 0;">For high-volume networks, agencies, and API integrations.</p>
                    
                    <div style="margin: 0 0 1.15rem 0;">
                        <span style="font-size: clamp(2rem, 5vw, 2.5rem); font-weight: 900; color: #0f172a; letter-spacing: -0.03em;">Custom</span>
                        <span style="color: #64748b; font-size: 0.825rem; font-weight: 600;">/ negotiated</span>
                    </div>

                    <div style="border-top: 1px solid #e2e8f0; padding-top: 1rem; margin-bottom: 1.25rem;">
                        <div class="feature-item">
                            <span class="feature-check blue">✓</span>
                            <span>Everything included in Pro</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check blue">✓</span>
                            <strong style="color: #0f172a;">Direct Reseller Developer REST API</strong>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check blue">✓</span>
                            <span>Unlimited Staff Accounts</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check blue">✓</span>
                            <span>Custom Bank Settlement Schedule</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check blue">✓</span>
                            <span>Dedicated VIP Account Manager</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check blue">✓</span>
                            <span>Custom 99.9% Uptime SLA Agreement</span>
                        </div>
                    </div>
                </div>

                <button wire:click="contactEnterprise" type="button" 
                        style="width: 100%; min-height: 46px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #0f172a; font-weight: 800; font-size: 0.9rem; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0.75rem 0.5rem; word-break: break-word;">
                    Contact Enterprise Sales 📞
                </button>
            </div>

        </div>

        {{-- Bottom "Skip with Starter" Action Box --}}
        <div class="bottom-starter-box">
            <div style="min-width: 0;">
                <div style="font-weight: 800; font-size: 0.9rem; color: #0f172a; word-break: break-word;">
                    Want to start with the Free Starter plan?
                </div>
                <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.15rem; word-break: break-word;">
                    ₦0 forever. Includes free hosted subdomain and standard wholesale rates.
                </div>
            </div>

            <button wire:click="continueStarter" type="button" 
                    style="padding: 0.6rem 1.15rem; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; font-weight: 800; color: #0f172a; cursor: pointer; white-space: nowrap; width: 100%; max-width: 240px; text-align: center; flex-shrink: 0;">
                Skip with Starter Plan →
            </button>
        </div>

        {{-- Security Note --}}
        <div style="text-align: center; color: #94a3b8; font-size: 0.75rem; line-height: 1.4; padding: 0 0.5rem;">
            🔒 Payments processed with bank-grade encryption via PayMint Africa. Cancel or switch plans anytime.
        </div>

    </div>
</div>
