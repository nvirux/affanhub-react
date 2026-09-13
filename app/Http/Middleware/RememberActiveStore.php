<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RememberActiveStore
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Filament::getTenant();
        $user = Filament::auth()->user();

        if ($tenant && $user && method_exists($user, 'recordActiveStore')) {
            $user->recordActiveStore($tenant->getKey());
        }

        return $next($request);
    }
}
