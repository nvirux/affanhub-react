<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTransactionPinSet
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if (! $user->hasTransactionPin()) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'requires_transaction_pin' => true,
                    'message' => 'Please set up your 4-digit transaction PIN before performing this action.',
                    'redirect' => route('transaction-pin.setup'),
                ], 403);
            }

            // Save intended URL so user returns to the exact page they were trying to open
            $request->session()->put('url.intended', $request->fullUrl());

            return redirect()->route('transaction-pin.setup')
                ->with('warning', 'Please create your 4-digit transaction PIN to continue.');
        }

        return $next($request);
    }
}
