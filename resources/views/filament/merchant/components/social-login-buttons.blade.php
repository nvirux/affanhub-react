@php
    $isRegister = request()->routeIs('*register*');
    $dividerText = $isRegister ? 'Or register with' : 'Or login with';
    $buttonText = $isRegister ? 'Sign up with Google' : 'Log in with Google';
@endphp

@if (session('error'))
    <div style="margin-top: 1rem; padding: 0.75rem 1rem; border-radius: 0.5rem; background-color: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; font-size: 0.875rem; font-weight: 500;">
        {{ session('error') }}
    </div>
@endif

<div class="affan-social-login-wrapper" style="width: 100%; margin-top: 1.5rem;">
    <div class="affan-social-divider" style="position: relative; display: flex; align-items: center; justify-content: center; margin: 1.25rem 0;">
        <div class="affan-divider-line" style="position: absolute; left: 0; right: 0; height: 1px; background-color: #e5e7eb;"></div>
        <span class="affan-divider-text" style="position: relative; padding: 0 0.85rem; font-size: 0.8125rem; color: #6b7280; font-weight: 500; background-color: #ffffff;">
            {{ $dividerText }}
        </span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.625rem; width: 100%;">
        <a href="{{ route('merchant.google.redirect') }}"
           class="affan-social-btn"
           style="display: flex; align-items: center; justify-content: center; width: 100%; height: 42px; padding: 0 1rem; border-radius: 0.5rem; border: 1px solid #e5e7eb; background-color: #ffffff; text-decoration: none; cursor: pointer; transition: all 0.15s ease; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
            <svg width="18" height="18" viewBox="0 0 24 24" style="width: 18px !important; height: 18px !important; min-width: 18px !important; min-height: 18px !important; max-width: 18px !important; max-height: 18px !important; margin-right: 0.625rem; flex-shrink: 0; display: inline-block;">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
            </svg>
            <span class="affan-social-btn-text" style="font-size: 0.875rem; font-weight: 500; color: #374151; white-space: nowrap;">
                {{ $buttonText }}
            </span>
        </a>
    </div>
</div>

<style>
    .affan-social-btn:hover {
        background-color: #f9fafb !important;
        border-color: #d1d5db !important;
    }
    .dark .affan-divider-line {
        background-color: rgba(255, 255, 255, 0.12) !important;
    }
    .dark .affan-divider-text {
        background-color: #111827 !important;
        color: #9ca3af !important;
    }
    .dark .affan-social-btn {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        box-shadow: none !important;
    }
    .dark .affan-social-btn:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
    }
    .dark .affan-social-btn-text {
        color: #f3f4f6 !important;
    }
</style>
