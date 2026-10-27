<?php

namespace App\Filament\Merchant\Pages\Auth;

use App\Models\Owner;
use Filament\Auth\Pages\EmailVerification\EmailVerificationPrompt as BasePrompt;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class EmailVerificationPrompt extends BasePrompt
{
    protected string $view = 'filament.merchant.auth.email-verification-prompt';

    public string $code = '';

    public function mount(): void
    {
        parent::mount();

        /** @var Owner $user */
        $user = $this->getVerifiable();

        // Ensure an OTP is queued/sent on prompt visit if not already active
        if (! Cache::has("owner_email_otp_{$user->id}")) {
            $user->sendEmailVerificationNotification();
        }
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Verify your Email Address';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Enter the 6-digit verification code sent to '.$this->getVerifiable()->getEmailForVerification();
    }

    public function verifyCode(): mixed
    {
        $this->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ], [
            'code.required' => 'Please enter the 6-digit verification code.',
            'code.size' => 'The verification code must be exactly 6 digits.',
            'code.regex' => 'The verification code must contain numbers only.',
        ]);

        /** @var Owner $user */
        $user = $this->getVerifiable();

        if (! $user->verifyEmailOtp($this->code)) {
            $this->addError('code', 'The verification code you entered is invalid or has expired. Please try again or request a new code.');

            Notification::make()
                ->title('Invalid Code')
                ->body('The verification code is incorrect or has expired.')
                ->danger()
                ->send();

            return null;
        }

        event(new Verified($user));

        // Track Meta Pixel CompleteRegistration for verified merchant account
        $testCode = request()->query('test_event_code')
            ?? session('meta_test_event_code')
            ?? env('META_TEST_EVENT_CODE');

        $pixelParams = [
            'content_name' => 'Merchant Account Verified',
            'status' => true,
        ];

        if (! empty($testCode)) {
            $pixelParams['test_event_code'] = $testCode;
        }

        session()->put('pixel_event', [
            'event' => 'CompleteRegistration',
            'params' => $pixelParams,
        ]);

        $jsonParams = json_encode($pixelParams);
        $this->js("
            try {
                if (typeof window.fbq === 'function') {
                    const testCode = localStorage.getItem('meta_test_event_code');
                    const params = {$jsonParams};
                    if (testCode && !params.test_event_code) {
                        params.test_event_code = testCode;
                    }
                    if (params.test_event_code) {
                        window.fbq('track', 'CompleteRegistration', params, { test_event_code: params.test_event_code });
                    } else {
                        window.fbq('track', 'CompleteRegistration', params);
                    }
                }
            } catch(e) {}
        ");

        Notification::make()
            ->title('Email Verified!')
            ->body('Your email address has been verified successfully.')
            ->success()
            ->send();

        return redirect()->intended(Filament::getUrl());
    }

    public function resendCode(): void
    {
        $userId = Filament::auth()->id();
        $rateLimitKey = 'resend-email-otp:'.$userId;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            Notification::make()
                ->title('Please wait')
                ->body("Please wait {$seconds} seconds before requesting a new code.")
                ->warning()
                ->send();

            return;
        }

        RateLimiter::hit($rateLimitKey, 60);

        /** @var Owner $user */
        $user = $this->getVerifiable();
        $user->sendEmailVerificationNotification();

        $this->code = '';
        $this->resetErrorBag();

        Notification::make()
            ->title('Code Resent!')
            ->body('A fresh 6-digit code has been sent to '.$user->getEmailForVerification())
            ->success()
            ->send();
    }

    public function logout(): mixed
    {
        Filament::auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to(Filament::getLoginUrl());
    }
}
