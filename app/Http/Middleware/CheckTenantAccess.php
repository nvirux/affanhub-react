<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();

        if ($tenant) {
            // 1. Check if store is suspended by admin
            if ($tenant->status === 'suspended') {
                abort(403, 'This storefront has been suspended by the platform administrator.');
            }

            // 2. Custom domain check: If accessing via a verified custom domain for this store, allow it to serve!
            $currentHost = $request->getHost();
            $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';

            // Check if host is not a subdomain of the base domain
            $isSubdomain = str_ends_with($currentHost, '.'.$baseDomain) || $currentHost === $baseDomain;

            if (! $isSubdomain) {
                // Check if this custom domain is an active verified domain registered to this store
                $isDomainActive = $tenant->domains()
                    ->where('domain', $currentHost)
                    ->where('is_routing_enabled', true)
                    ->where('status', 'verified')
                    ->exists();

                // If domain is active & verified, or store has custom_domain feature, allow it!
                if (! $isDomainActive && ! $tenant->hasFeature('custom_domain')) {
                    // Redirect to store's primary URL (never raw numeric ID)
                    $targetUrl = rtrim($tenant->getStoreUrl(), '/').$request->getRequestUri();

                    return redirect($targetUrl);
                }
            }
        }

        return $next($request);
    }
}
