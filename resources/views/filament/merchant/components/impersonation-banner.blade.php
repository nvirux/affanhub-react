@if(session()->has('impersonated_by_admin'))
    <style>
        .ah-imp-banner {
            width: 100%;
            background: #9a3412;
            color: #ffffff;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            z-index: 10;
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        @media (min-width: 640px) {
            .ah-imp-banner {
                padding: 0.625rem 1.5rem;
            }
        }
        .ah-imp-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .ah-imp-text-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
        }
        .ah-imp-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fdba74;
            box-shadow: 0 0 0 2px rgba(253, 186, 116, 0.35);
            flex-shrink: 0;
        }
        .ah-imp-title-mobile {
            display: inline;
            font-weight: 800;
            letter-spacing: -0.2px;
            font-size: 0.8125rem;
            color: #ffffff;
            white-space: nowrap;
        }
        .ah-imp-title-desktop {
            display: none;
            font-weight: 800;
            letter-spacing: -0.2px;
            font-size: 0.875rem;
            color: #ffffff;
            white-space: nowrap;
        }
        @media (min-width: 640px) {
            .ah-imp-title-mobile {
                display: none;
            }
            .ah-imp-title-desktop {
                display: inline;
            }
        }
        .ah-imp-desc {
            display: none;
            color: #ffedd5;
            font-size: 0.8125rem;
        }
        @media (min-width: 840px) {
            .ah-imp-desc {
                display: inline;
            }
        }
        .ah-imp-link {
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
            background: rgba(255, 255, 255, 0.16);
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            transition: background 0.15s ease;
        }
        .ah-imp-link:hover,
        .ah-imp-link:focus,
        .ah-imp-link:active {
            background: rgba(255, 255, 255, 0.28);
            text-decoration: none !important;
            color: #ffffff !important;
        }
        .ah-imp-link * {
            text-decoration: none !important;
        }
    </style>

    <div class="ah-imp-banner" role="alert">
        <div class="ah-imp-inner">
            <div class="ah-imp-text-group">
                <span class="ah-imp-dot"></span>
                <span class="ah-imp-title-mobile">
                    Impersonating: {{ \Illuminate\Support\Str::limit(session('impersonated_store_name', 'Store'), 20) }}
                </span>
                <span class="ah-imp-title-desktop">
                    Super Admin Impersonation
                </span>
                <span class="ah-imp-desc">
                    Viewing <strong>{{ session('impersonated_store_name', 'this store') }}</strong>. Actions reflect live on store account.
                </span>
            </div>

            <a href="{{ route('impersonate.leave') }}" class="ah-imp-link" title="Exit impersonation and return to Super Admin">
                <span>Exit</span>
                <x-filament::icon
                    icon="heroicon-m-arrow-right"
                    class="h-3.5 w-3.5 shrink-0"
                />
            </a>
        </div>
    </div>
@endif
