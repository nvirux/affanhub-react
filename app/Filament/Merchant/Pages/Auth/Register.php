<?php

namespace App\Filament\Merchant\Pages\Auth;

use App\Models\Owner;
use App\Rules\MerchantRegistrationThrottle;
use App\Rules\TrustedEmailDomain;
use App\Rules\TurnstileRule;
use Filament\Actions\Action;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;

class Register extends BaseRegister
{
    public function getHeading(): string|Htmlable
    {
        return 'Create your Merchant Account';
    }

    public function getSubheading(): string|Htmlable|null
    {
        $loginHtml = filament()->hasLogin() ? $this->loginAction->toHtml() : '';

        return new HtmlString(
            'Start vending data, airtime, and utility bills under your own brand.'.
            ($loginHtml ? '<div class="mt-2 text-sm text-gray-600 dark:text-gray-400">Already have an account? '.$loginHtml.'</div>' : '')
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Invisible Anti-Bot Honeypot Field
                TextInput::make('website_url')
                    ->hiddenLabel()
                    ->placeholder('')
                    ->rule('prohibited')
                    ->extraFieldWrapperAttributes([
                        'style' => 'display:none !important; position:absolute; left:-9999px; height:0; width:0; overflow:hidden;',
                    ])
                    ->extraAttributes([
                        'style' => 'display:none !important; position:absolute; left:-9999px;',
                        'tabindex' => '-1',
                        'autocomplete' => 'off',
                    ])
                    ->dehydrated(false),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPhoneFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getTermsFormComponent(),
                $this->getTurnstileFormComponent(),
            ]);
    }

    protected function getTurnstileFormComponent(): Component
    {
        return ViewField::make('turnstile_token')
            ->hiddenLabel()
            ->view('filament.components.turnstile')
            ->rule(new TurnstileRule('merchant_auth'))
            ->dehydrated(false);
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Full Name')
            ->required()
            ->maxLength(70)
            ->rules([
                'regex:/^[a-zA-Z\s\.\'\-]+$/',
            ])
            ->validationMessages([
                'regex' => 'Please enter a valid personal name (letters and spaces only). Promotional text and URLs are not permitted.',
            ])
            ->placeholder('e.g. Al-Amin Abdullahi')
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email Address')
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel())
            ->rules([
                new TrustedEmailDomain,
                new MerchantRegistrationThrottle(3, 86400),
            ])
            ->placeholder('alamin@gmail.com')
            ->helperText('Please use your real and active email address. A 6-digit verification code will be sent to your inbox to verify your account.');
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('Phone Number')
            ->tel()
            ->required()
            ->length(11)
            ->rules(['digits:11', 'regex:/^0[789][01]\d{8}$/'])
            ->validationMessages([
                'digits' => 'Phone number must be exactly 11 digits.',
                'regex' => 'Please enter a valid 11-digit Nigerian phone number (e.g. 08012345678).',
            ])
            ->unique($this->getUserModel())
            ->placeholder('08012345678')
            ->helperText('Used for important security and account recovery notifications.');
    }

    protected function getTermsFormComponent(): Component
    {
        $rootUrl = config('app.url') ? rtrim(config('app.url'), '/') : 'https://affanhub.com';

        return Checkbox::make('terms')
            ->label(new HtmlString('I agree to the <a href="'.$rootUrl.'/terms" target="_blank" style="color: #d97706; text-decoration: underline; font-weight: 600;">Terms of Service</a> and <a href="'.$rootUrl.'/privacy" target="_blank" style="color: #d97706; text-decoration: underline; font-weight: 600;">Privacy Policy</a>.'))
            ->accepted()
            ->validationMessages([
                'accepted' => 'You must accept the terms of service to create your account.',
            ])
            ->dehydrated(false);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::min(8))
            ->same('passwordConfirmation')
            ->validationAttribute('password');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label('Confirm Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label('Create Account')
            ->submit('register');
    }

    /**
     * Record successful registration against network IP rate limit.
     */
    protected function afterRegister(): void
    {
        $ip = request()->ip() ?: '127.0.0.1';
        RateLimiter::hit('merchant_register_ip:'.$ip, 86400); // 24 hours decay
    }

    /**
     * Send only the 6-digit OTP verification email, preventing the default link email.
     */
    protected function sendEmailVerificationNotification(Model $user): void
    {
        if (! $user instanceof MustVerifyEmail) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            return;
        }

        if ($user instanceof Owner) {
            $user->sendEmailVerificationNotification();
        }
    }
}
