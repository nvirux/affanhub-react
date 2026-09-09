<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetLoginPinNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;

class ForgotPinController extends Controller
{
    /**
     * Display the form to request a PIN reset link.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/forgot-pin', [
            'status' => session('status'),
            'debugResetUrl' => app()->environment('local', 'testing') ? session('debug_reset_url') : null,
            'initialIdentifier' => $request->query('identifier', ''),
        ]);
    }

    /**
     * Send a PIN reset link to the user's email.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
        ], [
            'identifier.required' => 'Please enter your phone number or email address.',
        ]);

        $rawIdentifier = trim($request->identifier);
        $normalizedPhone = preg_replace('/[^0-9+]/', '', $rawIdentifier);
        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;

        $user = User::query()
            ->when($tenantId, fn ($query) => $query->where('store_id', $tenantId))
            ->where(function ($query) use ($rawIdentifier, $normalizedPhone) {
                $query->where('email', $rawIdentifier)
                    ->orWhere('phone', $rawIdentifier);

                if (! empty($normalizedPhone) && $normalizedPhone !== $rawIdentifier) {
                    $query->orWhere('phone', $normalizedPhone);
                }
            })
            ->first();

        if (! $user) {
            return back()->withErrors([
                'identifier' => 'No account found matching this phone number or email address.',
            ]);
        }

        $token = Password::broker()->createToken($user);
        $resetUrl = route('pin.reset', ['token' => $token, 'email' => $user->email]);

        $user->notify(new ResetLoginPinNotification($token, $resetUrl));

        if (app()->environment('local', 'testing')) {
            session()->flash('debug_reset_url', $resetUrl);
        }

        return back()->with('status', 'A Login PIN reset link has been sent to your email address ('.$user->email.').');
    }

    /**
     * Display the PIN reset view with the token.
     */
    public function edit(Request $request, string $token): Response|RedirectResponse
    {
        $email = $request->query('email');

        if (empty($email)) {
            return redirect()->route('login');
        }

        return Inertia::render('auth/reset-pin', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Reset the user's 4-digit Login PIN using the token.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'pin' => ['required', 'digits:4', 'confirmed'],
        ], [
            'pin.digits' => 'Your new Login PIN must be exactly 4 digits.',
            'pin.confirmed' => 'The Login PIN confirmation does not match.',
        ]);

        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : null;

        $user = User::query()
            ->when($tenantId, fn ($query) => $query->where('store_id', $tenantId))
            ->where('email', $request->email)
            ->first();

        if (! $user || ! Password::broker()->tokenExists($user, $request->token)) {
            return back()->withErrors([
                'pin' => 'This PIN reset link is invalid or has expired. Please request a new one.',
            ]);
        }

        $user->update([
            'login_pin_hash' => $request->pin,
            'login_pin_enabled' => true,
        ]);

        Password::broker()->deleteToken($user);

        return redirect()->route('login')->with('status', 'Your 4-digit Login PIN has been reset successfully! You can now log in.');
    }
}
