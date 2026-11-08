<?php

namespace App\Http\Middleware;

use App\Models\Domain;
use Closure;
use Illuminate\Http\Request;

class CheckTenantAccess
{
    public function handle(Request $request, Closure $next): mixed
    {
        $tenant = tenant();

        if ($tenant) {
            // 1. Check if store is suspended by admin
            if ($tenant->status === 'suspended') {
                abort(403, 'This storefront has been suspended by the platform administrator.');
            }

            // 2. Strict multi-tenant session isolation: Prevent cross-store customer session reuse
            if ($request->user('web') && (int) $request->user('web')->store_id !== (int) $tenant->id) {
                auth()->guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->guest(route('login'));
            }

            // 2. Custom domain check: If accessing via a verified custom domain for this store, allow it to serve!
            $currentHost = $request->getHost();
            $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';

            // Auto-redirect www. to non-www for custom domains
            if (str_starts_with($currentHost, 'www.') && ! in_array($currentHost, config('tenancy.central_domains', []))) {
                $nonWwwHost = substr($currentHost, 4);
                if ($tenant->domains()->where('domain', $nonWwwHost)->exists()) {
                    $scheme = ($request->isSecure() || ! app()->environment('local', 'testing')) ? 'https' : 'http';
                    $port = $request->getPort();
                    $portSuffix = ($port && ! in_array((int) $port, [80, 443]) && ! str_contains($nonWwwHost, ':')) ? ":{$port}" : '';

                    return redirect()->away("{$scheme}://{$nonWwwHost}{$portSuffix}".$request->getRequestUri(), 301);
                }
            }

            // Check if host is a platform subdomain
            $isSubdomain = str_ends_with($currentHost, '.'.$baseDomain) || $currentHost === $baseDomain;
            if (! $isSubdomain) {
                foreach (Domain::platformDomainPatterns() as $pattern) {
                    if (str_ends_with($currentHost, '.'.$pattern)) {
                        $isSubdomain = true;
                        break;
                    }
                }
            }

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
            } else {
                // 3. Automated Subdomain -> Custom Domain 301 SEO Redirect:
                // When a merchant activates a healthy primary custom domain (e.g. adeoladata.com),
                // permanently 301-redirect visitors hitting their platform subdomain (adeola.affanhub.com)
                // so search engines transfer 100% of backlinks, indexing, and SEO authority to the custom domain.
                if ($request->isMethodSafe()) {
                    $primaryDomain = $tenant->getPrimaryDomain();

                    if ($primaryDomain && $primaryDomain !== $currentHost) {
                        $customDomain = $tenant->domains()
                            ->where('domain', $primaryDomain)
                            ->first();

                        if ($customDomain && $customDomain->isCustom() && $customDomain->isHealthy()) {
                            $scheme = ($request->isSecure() || ! app()->environment('local', 'testing')) ? 'https' : 'http';
                            $port = $request->getPort();
                            $portSuffix = ($port && ! in_array((int) $port, [80, 443]) && ! str_contains($primaryDomain, ':')) ? ":{$port}" : '';
                            $targetUrl = "{$scheme}://{$primaryDomain}{$portSuffix}".$request->getRequestUri();

                            return redirect()->away($targetUrl, 301);
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
