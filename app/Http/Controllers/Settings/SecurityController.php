<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PasswordUpdateRequest;
use App\Http\Requests\Settings\TwoFactorAuthenticationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class SecurityController extends Controller
{
    /**
     * Show the user's security settings page.
     */
    public function edit(TwoFactorAuthenticationRequest $request): Response
    {
        $user = $request->user();

        $props = [
            'canManageTwoFactor' => Features::canManageTwoFactorAuthentication(),
            'canManagePasskeys' => false,
            'passkeys' => [],
            'passwordRules' => Password::defaults()->toPasswordRulesString(),
            'hasPassword' => $user->hasPassword(),
            'hasLoginPin' => $user->hasLoginPin(),
            'hasTransactionPin' => $user->hasTransactionPin(),
        ];

        if (Features::canManageTwoFactorAuthentication()) {
            $request->ensureStateIsValid();

            $props['twoFactorEnabled'] = $user->hasEnabledTwoFactorAuthentication();
            $props['requiresConfirmation'] = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
        }

        return Inertia::render('settings/security', $props);
    }

    /**
     * Update the user's password.
     */
    public function update(PasswordUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $wasPasswordless = ! $user->hasPassword();

        $user->update([
            'password' => $request->password,
        ]);

        $message = $wasPasswordless ? __('Password created successfully.') : __('Password updated.');
        Inertia::flash('toast', ['type' => 'success', 'message' => $message]);

        return back();
    }

    /**
     * Update or set the user's 4-digit Login PIN.
     */
    public function updateLoginPin(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'pin' => ['required', 'digits:4', 'confirmed'],
        ];

        if ($user->hasLoginPin()) {
            $rules['current_pin'] = ['required', 'digits:4'];
        } elseif ($user->hasPassword()) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $request->validate($rules, [
            'pin.digits' => 'Your new Login PIN must be exactly 4 digits.',
            'pin.confirmed' => 'The Login PIN confirmation does not match.',
            'current_pin.digits' => 'Your current Login PIN must be 4 digits.',
        ]);

        if ($user->hasLoginPin()) {
            if (! Hash::check($request->current_pin, $user->login_pin_hash)) {
                return back()->withErrors(['current_pin' => 'Your current Login PIN is incorrect.']);
            }
        }

        $user->update([
            'login_pin_hash' => $request->pin,
            'login_pin_enabled' => true,
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Login PIN updated successfully.')]);

        return back();
    }

    /**
     * Update or set the user's 4-digit Transaction PIN.
     */
    public function updateTransactionPin(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'pin' => ['required', 'digits:4', 'confirmed'],
        ];

        if ($user->hasTransactionPin()) {
            $rules['current_pin'] = ['required', 'digits:4'];
        } else {
            if ($user->hasLoginPin()) {
                $rules['login_pin'] = ['required', 'digits:4'];
            } elseif ($user->hasPassword()) {
                $rules['current_password'] = ['required', 'current_password'];
            }
        }

        $request->validate($rules, [
            'pin.digits' => 'Your Transaction PIN must be exactly 4 digits.',
            'pin.confirmed' => 'The Transaction PIN confirmation does not match.',
            'current_pin.digits' => 'Your current Transaction PIN must be 4 digits.',
            'login_pin.digits' => 'Your Login PIN must be 4 digits.',
        ]);

        if ($user->hasTransactionPin()) {
            if (! Hash::check($request->current_pin, $user->transaction_pin_hash)) {
                return back()->withErrors(['current_pin' => 'Your current Transaction PIN is incorrect.']);
            }
        } else {
            if ($user->hasLoginPin() && ! Hash::check($request->login_pin, $user->login_pin_hash)) {
                return back()->withErrors(['login_pin' => 'Your Login PIN is incorrect.']);
            }
        }

        $user->update([
            'transaction_pin_hash' => $request->pin,
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Transaction PIN updated successfully.')]);

        return back();
    }
}
