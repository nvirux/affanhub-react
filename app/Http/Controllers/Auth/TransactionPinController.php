<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TransactionPinController extends Controller
{
    /**
     * Show the transaction PIN setup form.
     */
    public function create(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        if ($user && $user->hasTransactionPin()) {
            return redirect()->intended(route('dashboard'));
        }

        return Inertia::render('auth/setup-transaction-pin');
    }

    /**
     * Store the newly created transaction PIN.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pin' => ['required', 'digits:4', 'confirmed'],
        ], [
            'pin.digits' => 'Your 4-digit Transaction PIN must contain exactly 4 numeric digits.',
            'pin.confirmed' => 'The Transaction PIN confirmation does not match.',
        ]);

        $user = $request->user();

        $user->update([
            'transaction_pin_hash' => $request->pin,
        ]);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Your 4-digit Transaction PIN has been set successfully!');
    }
}
