@php
    $tenant = \Filament\Facades\Filament::getTenant();
    $subscription = $tenant?->subscription;
@endphp

@if(! session()->has('impersonated_by_admin') && $subscription && ($subscription->isInGracePeriod() || $subscription->isExpiringSoon(3)))
    @php
        $isInGrace = $subscription->isInGracePeriod();
        $planName = $subscription->plan?->name ?? 'Pro';
        $billingUrl = \App\Filament\Merchant\Pages\Billing::getUrl(['tenant' => $tenant->public_id]);
        $bgColor = $isInGrace ? '#b91c1c' : '#b45309';
        $textColor = '#ffffff';
        $subColor = $isInGrace ? '#fecaca' : '#ffedd5';
        $btnBg = 'rgba(255, 255, 255, 0.18)';
        $btnBgHover = 'rgba(255, 255, 255, 0.28)';
    @endphp

    <style>
        .ah-sub-banner {
            width: 100%;
            background: {{ $bgColor }};
            color: {{ $textColor }};
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            z-index: 10;
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        @media (min-width: 640px) {
            .ah-sub-banner {
                padding: 0.625rem 1.5rem;
            }
        }
        .ah-sub-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .ah-sub-text-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }
        .ah-sub-title-mobile {
            display: inline;
            font-weight: 800;
            letter-spacing: -0.2px;
            font-size: 0.8125rem;
            color: #ffffff;
            white-space: nowrap;
        }
        .ah-sub-title-desktop {
            display: none;
            font-weight: 800;
            letter-spacing: -0.2px;
            font-size: 0.875rem;
            color: #ffffff;
            white-space: nowrap;
        }
        @media (min-width: 640px) {
            .ah-sub-title-mobile {
                display: none;
            }
            .ah-sub-title-desktop {
                display: inline;
            }
        }
        .ah-sub-desc {
            display: none;
            color: {{ $subColor }};
            font-size: 0.8125rem;
        }
        @media (min-width: 840px) {
            .ah-sub-desc {
                display: inline;
            }
        }
        .ah-sub-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #ffffff !important;
            font-weight: 700;
            text-decoration: none !important;
            white-space: nowrap;
            font-size: 0.8125rem;
            line-height: 1;
            flex-shrink: 0;
            border: none !important;
            outline: none !important;
            background: {{ $btnBg }};
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            transition: background 0.15s ease;
        }
        .ah-sub-link:hover,
        .ah-sub-link:focus,
        .ah-sub-link:active {
            background: {{ $btnBgHover }};
            text-decoration: none !important;
            color: #ffffff !important;
        }
        .ah-sub-link * {
            text-decoration: none !important;
        }
    </style>

    <div class="ah-sub-banner" role="alert">
        <div class="ah-sub-inner">
            <div class="ah-sub-text-group">
                @if($isInGrace)
                    <span class="ah-sub-title-mobile">
                        🚨 Grace Period: {{ $subscription->daysUntilGraceEnd() }} {{ \Illuminate\Support\Str::plural('Day', $subscription->daysUntilGraceEnd()) }} Left
                    </span>
                    <span class="ah-sub-title-desktop">
                        Grace Period Active: {{ $planName }} Plan Expired
                    </span>
                    <span class="ah-sub-desc">
                        You have <strong>{{ $subscription->daysUntilGraceEnd() }} {{ \Illuminate\Support\Str::plural('day', $subscription->daysUntilGraceEnd()) }}</strong> left until <strong>{{ $subscription->grace_ends_at?->format('M j, Y') }}</strong> before downgrade to Starter.
                    </span>
                @else
                    <span class="ah-sub-title-mobile">
                        ⏳ {{ $planName }} Plan: {{ $subscription->daysUntilExpiration() }} {{ \Illuminate\Support\Str::plural('Day', $subscription->daysUntilExpiration()) }} Left
                    </span>
                    <span class="ah-sub-title-desktop">
                        {{ $planName }} Plan Expires in {{ $subscription->daysUntilExpiration() }} {{ \Illuminate\Support\Str::plural('Day', $subscription->daysUntilExpiration()) }}
                    </span>
                    <span class="ah-sub-desc">
                        Renew by <strong>{{ $subscription->ends_at?->format('F j, Y') }}</strong> to maintain custom domain routing & wholesale data rates.
                    </span>
                @endif
            </div>

            <a href="{{ $billingUrl }}" class="ah-sub-link">
                <span>{{ $isInGrace ? 'Renew Plan Now' : 'Renew Subscription' }}</span>
                <x-filament::icon
                    icon="heroicon-m-arrow-right"
                    class="h-3.5 w-3.5 shrink-0"
                />
            </a>
        </div>
    </div>
@endif
