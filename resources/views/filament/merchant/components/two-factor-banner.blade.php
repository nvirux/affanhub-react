@php
    $user = auth()->guard('owner')->user() ?? filament()->auth()->user() ?? auth()->user();
@endphp

@if(! session()->has('impersonated_by_admin') && $user && method_exists($user, 'hasAppAuthentication') && ! $user->hasAppAuthentication())
    @php
        $tenant = filament()->getTenant();
        $profileUrl = $tenant ? route('filament.merchant.auth.profile', ['tenant' => $tenant->public_id]) : (filament()->hasProfile() ? filament()->getProfileUrl() : '#');
    @endphp

    <style>
        .ah-2fa-banner {
            width: 100%;
            background: #c2410c;
            color: #ffffff;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            z-index: 10;
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        @media (min-width: 640px) {
            .ah-2fa-banner {
                padding: 0.625rem 1.5rem;
            }
        }
        .ah-2fa-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .ah-2fa-text-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }
        .ah-2fa-title {
            font-weight: 800;
            letter-spacing: -0.2px;
            font-size: 0.8125rem;
            color: #ffffff;
            white-space: nowrap;
        }
        @media (min-width: 640px) {
            .ah-2fa-title {
                font-size: 0.875rem;
            }
        }
        .ah-2fa-desc {
            display: none;
            color: #ffedd5;
            font-size: 0.8125rem;
        }
        @media (min-width: 840px) {
            .ah-2fa-desc {
                display: inline;
            }
        }
        .ah-2fa-link {
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
            transition: opacity 0.15s ease;
        }
        .ah-2fa-link:hover,
        .ah-2fa-link:focus,
        .ah-2fa-link:active {
            opacity: 0.9;
            text-decoration: none !important;
            color: #ffffff !important;
        }
        .ah-2fa-link * {
            text-decoration: none !important;
        }
    </style>

    <div class="ah-2fa-banner">
        <div class="ah-2fa-inner">
            <div class="ah-2fa-text-group">
                <span class="ah-2fa-title">
                    Setup Two-Factor Authenticator
                </span>
                <span class="ah-2fa-desc">
                    Adds an extra layer of security to your account to protect you from unauthorised transactions.
                </span>
            </div>

            <a href="{{ $profileUrl }}" class="ah-2fa-link">
                <span>Setup 2FA Now</span>
                <x-filament::icon
                    icon="heroicon-m-arrow-up-right"
                    class="h-3.5 w-3.5 shrink-0"
                />
            </a>
        </div>
    </div>
@endif
