<x-filament-panels::page.simple>
    <style>
        .otp-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-top: 0.5rem;
        }
        .otp-input-field {
            width: 100%;
            max-width: 320px;
            height: 60px;
            text-align: center;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 12px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            border-radius: 14px;
            border: 2px solid #e5e7eb;
            background-color: #ffffff;
            color: #111827;
            padding: 0 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
            outline: none;
            box-sizing: border-box;
        }
        .otp-input-field:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.18);
            background-color: #ffffff;
        }
        @media (prefers-color-scheme: dark) {
            .otp-input-field {
                background-color: #18181b;
                border-color: #3f3f46;
                color: #f4f4f5;
            }
            .otp-input-field:focus {
                border-color: #f59e0b;
                box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.25);
                background-color: #27272a;
            }
        }
        .otp-btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 320px;
            height: 48px;
            padding: 0 24px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.2px;
            color: #ffffff;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(217, 119, 6, 0.3);
            transition: all 0.2s ease;
        }
        .otp-btn-submit:hover:not(:disabled) {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(217, 119, 6, 0.4);
        }
        .otp-btn-submit:active:not(:disabled) {
            transform: translateY(0);
        }
        .otp-btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .otp-error-msg {
            margin-top: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #dc2626;
            text-align: center;
        }
        .otp-sub-actions {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
            width: 100%;
            font-size: 13px;
            color: #6b7280;
        }
        @media (prefers-color-scheme: dark) {
            .otp-sub-actions {
                border-top-color: #27272a;
                color: #a1a1aa;
            }
        }
        .otp-link-resend {
            background: none;
            border: none;
            padding: 0;
            color: #d97706;
            font-weight: 700;
            cursor: pointer;
            text-decoration: underline;
            margin-left: 4px;
        }
        .otp-link-resend:hover {
            color: #b45309;
        }
        .otp-link-signout {
            background: none;
            border: none;
            padding: 0;
            color: #9ca3af;
            font-size: 12px;
            cursor: pointer;
            text-decoration: underline;
            margin-left: 4px;
        }
        .otp-link-signout:hover {
            color: #6b7280;
        }
        .otp-spinner {
            animation: otp-spin 1s linear infinite;
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            margin-right: 8px;
        }
        .otp-loading-spinner-wrap {
            align-items: center;
            justify-content: center;
        }
        @keyframes otp-spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <div class="otp-box">
        <form wire:submit.prevent="verifyCode" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
            <p style="font-size: 14px; font-weight: 600; color: #4b5563; margin-bottom: 12px; text-align: center;">
                Enter 6-digit verification code:
            </p>

            <div style="width: 100%; display: flex; justify-content: center; margin-bottom: 8px;">
                <input
                    type="text"
                    wire:model="code"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    autocomplete="one-time-code"
                    autofocus
                    placeholder="••••••"
                    class="otp-input-field"
                />
            </div>

            @error('code')
                <div class="otp-error-msg">
                    {{ $message }}
                </div>
            @enderror

            <div style="width: 100%; display: flex; justify-content: center; margin-top: 18px;">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="otp-btn-submit"
                >
                    <span wire:loading.remove wire:target="verifyCode">
                        Verify Account
                    </span>
                    <span wire:loading.flex wire:target="verifyCode" class="otp-loading-spinner-wrap">
                        <span class="otp-spinner"></span>
                        Verifying...
                    </span>
                </button>
            </div>
        </form>

        <div class="otp-sub-actions">
            <div>
                Didn't receive the code?
                <button
                    type="button"
                    wire:click="resendCode"
                    wire:loading.attr="disabled"
                    class="otp-link-resend"
                >
                    <span wire:loading.remove wire:target="resendCode">Resend Code</span>
                    <span wire:loading wire:target="resendCode">Sending...</span>
                </button>
            </div>

            <div>
                Wrong email address?
                <button
                    type="button"
                    wire:click="logout"
                    class="otp-link-signout"
                >
                    Sign out
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page.simple>
