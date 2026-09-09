<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class TenantPinSetupController extends Controller
{
    /**
     * Display the PIN setup form.
     */
    public function show(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        // Safeguard: Only accessible immediately after password authentication or valid recovery
        if (! $user || ($user->login_pin_enabled && ! empty($user->login_pin_hash)) || ! $request->session()->has('needs_pin_setup')) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('auth/setup-pin');
    }

    /**
     * Store the newly created 4-digit Login PIN.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Safeguard: Only process if session is authorized
        if (! $user || ($user->login_pin_enabled && ! empty($user->login_pin_hash)) || ! $request->session()->has('needs_pin_setup')) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'pin' => ['required', 'digits:4', 'confirmed'],
        ], [
            'pin.digits' => 'Your Login PIN must be exactly 4 digits.',
            'pin.confirmed' => 'The Login PIN confirmation does not match.',
        ]);

        $user->update([
            'login_pin_hash' => Hash::make($request->pin),
            'login_pin_enabled' => true,
        ]);

        $request->session()->forget('needs_pin_setup');

        return redirect()->route('dashboard')->with('status', 'Your 4-digit Login PIN has been created successfully!');
    }
}
