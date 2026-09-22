<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMerchantHasPhone
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $owner = Auth::guard('owner')->user();

        if ($owner && empty($owner->phone)) {
            if ($request->routeIs('merchant.phone.*') || $request->routeIs('merchant.google.*') || $request->is('*/logout') || $request->is('logout')) {
                return $next($request);
            }

            return redirect()->route('merchant.phone.complete');
        }

        return $next($request);
    }
}
