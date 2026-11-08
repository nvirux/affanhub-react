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

        // If accessing a non-tenant-scoped route (like /profile) in standard layout mode,
        // resolve and bind the active tenant so the topbar and navigation render without null tenant errors.
        if (! $tenant && $user) {
            $activeStore = null;

            if (! empty($user->last_active_store_id)) {
                $activeStore = $user->stores()->where('stores.id', $user->last_active_store_id)->first();
            }

            $activeStore ??= $user->stores()->first();

            if ($activeStore) {
                Filament::setTenant($activeStore);
            }
        }

        return $next($request);
    }
}
