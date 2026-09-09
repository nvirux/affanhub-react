<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Fortify::ignoreRoutes();

        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse
            {
                public function toResponse($request)
                {
                    $user = $request->user();

                    if ($user && ! $user->login_pin_enabled) {
                        $request->session()->put('needs_pin_setup', true);

                        return redirect()->route('pin.setup');
                    }

                    return redirect()->intended(route('dashboard'));
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            $identifier = trim((string) ($request->input(Fortify::username()) ?? $request->input('phone') ?? $request->input('email') ?? $request->input('identifier')));
            if (empty($identifier)) {
                return null;
            }

            $normalizedPhone = preg_replace('/[^0-9+]/', '', $identifier);
            $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;

            $user = User::query()
                ->when($tenantId, fn ($query) => $query->where('store_id', $tenantId))
                ->where(function ($query) use ($identifier, $normalizedPhone) {
                    $query->where('email', $identifier)
                        ->orWhere('phone', $identifier);

                    if (! empty($normalizedPhone) && $normalizedPhone !== $identifier) {
                        $query->orWhere('phone', $normalizedPhone);
                    }
                })
                ->first();

            if (! $user) {
                return null;
            }

            $credential = (string) ($request->input('pin') ?? $request->input('login_pin') ?? $request->input('password'));

            // Check Login PIN if enabled
            if ($user->login_pin_enabled && ! empty($user->login_pin_hash)) {
                if (Hash::check($credential, $user->login_pin_hash)) {
                    return $user;
                }
            }

            // Fallback to legacy password
            if (! empty($user->password)) {
                if (Hash::check($credential, $user->password)) {
                    return $user;
                }
            }

            return null;
        });
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn (Request $request) => Inertia::render('auth/login', [
            'canResetPassword' => Features::enabled(Features::resetPasswords()),
            'status' => $request->session()->get('status'),
        ]));

        Fortify::resetPasswordView(fn (Request $request) => Inertia::render('auth/reset-password', [
            'email' => $request->email,
            'token' => $request->route('token'),
            'passwordRules' => Password::defaults()->toPasswordRulesString(),
        ]));

        Fortify::requestPasswordResetLinkView(fn (Request $request) => Inertia::render('auth/forgot-password', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::verifyEmailView(fn (Request $request) => Inertia::render('auth/verify-email', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::registerView(fn () => Inertia::render('auth/register', [
            'passwordRules' => Password::defaults()->toPasswordRulesString(),
        ]));

        Fortify::twoFactorChallengeView(fn () => Inertia::render('auth/two-factor-challenge'));

        Fortify::confirmPasswordView(fn () => Inertia::render('auth/confirm-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $identifier = $request->input(Fortify::username()) ?? $request->input('phone') ?? $request->input('email') ?? $request->input('identifier') ?? '';
            $throttleKey = Str::transliterate(Str::lower($identifier).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('passkeys', function (Request $request) {
            return Limit::perMinute(10)->by(
                ($request->input('credential.id') ?: $request->session()->getId()).'|'.$request->ip(),
            );
        });
    }
}
