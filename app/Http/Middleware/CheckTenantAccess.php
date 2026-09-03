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

            // 2. Check custom domain access limit
            // If they are accessing the store via a custom domain, they must have the custom_domain entitlement active!
            $currentHost = $request->getHost();
            $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
            
            // Check if host is not a subdomain of the base domain
            $isSubdomain = str_ends_with($currentHost, '.' . $baseDomain) || $currentHost === $baseDomain;
            
            if (!$isSubdomain) {
                // They are using a custom domain! Check entitlement.
                if (!$tenant->hasFeature('custom_domain')) {
                    // Redirect them to their main subdomain URL
                    $subdomainUrl = $request->getScheme() . '://' . $tenant->id . '.' . $baseDomain . $request->getRequestUri();
                    return redirect($subdomainUrl);
                }
            }
        }

        return $next($request);
    }
}
