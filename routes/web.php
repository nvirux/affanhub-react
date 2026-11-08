<?php

use App\Http\Controllers\Auth\MerchantGoogleAuthController;
use App\Http\Controllers\Auth\MerchantPhoneCompletionController;
use App\Http\Controllers\BillingCheckoutCallbackController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\Merchant\CloudflareDomainConnectController;
use App\Http\Controllers\Merchant\StaffInvitationController;
use App\Http\Controllers\MobileAppDownloadController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\CheckTenantAccess;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

// Canonical 301 Redirect from www to main domain
$appHost = parse_url(config('app.url'), PHP_URL_HOST);
$cleanHost = $appHost ? preg_replace('/^www\./i', '', $appHost) : null;
if ($cleanHost && $cleanHost !== 'localhost' && $cleanHost !== '127.0.0.1') {
    Route::domain('www.'.$cleanHost)->group(function () use ($cleanHost) {
        Route::any('/{any?}', function () use ($cleanHost) {
            $scheme = request()->getScheme();
            $path = request()->getRequestUri();

            return redirect("{$scheme}://{$cleanHost}{$path}", 301);
        })->where('any', '.*');
    });
}

Route::get('/', HomeController::class)->name('home');

// Impersonation Routes
Route::get('/impersonate/consume', [ImpersonationController::class, 'consume'])->name('impersonate.consume');
Route::get('/impersonate/leave', [ImpersonationController::class, 'leave'])->name('impersonate.leave');

// PayMint Checkout Callback
Route::get('/billing/callback/{tenant:public_id}', [BillingCheckoutCallbackController::class, 'handle'])
    ->name('merchant.billing.callback');

// Mobile App Direct APK Download Route
Route::get('/apps/download/{store:public_id}', [MobileAppDownloadController::class, 'download'])
    ->name('merchant.mobile-app.download');

// Staff Invitation Acceptance Routes
Route::get('/invitations/{token}', [StaffInvitationController::class, 'show'])->name('merchant.invitation.show');
Route::post('/invitations/{token}/accept', [StaffInvitationController::class, 'acceptExisting'])->name('merchant.invitation.accept-existing');
Route::post('/invitations/{token}/register', [StaffInvitationController::class, 'registerAndAccept'])->name('merchant.invitation.register');

// Merchant Google OAuth Routes
Route::get('/auth/google/redirect', [MerchantGoogleAuthController::class, 'redirect'])->name('merchant.google.redirect');
Route::get('/auth/google/callback', [MerchantGoogleAuthController::class, 'callback'])->name('merchant.google.callback');

// Merchant Phone Completion Routes
Route::get('/auth/complete-phone', [MerchantPhoneCompletionController::class, 'create'])->name('merchant.phone.complete');
Route::post('/auth/complete-phone', [MerchantPhoneCompletionController::class, 'store'])->name('merchant.phone.store');

// Merchant Cloudflare 1-Click Connect Routes
Route::get('/merchant/cloudflare/connect/{domain}', [CloudflareDomainConnectController::class, 'connect'])
    ->name('merchant.cloudflare.connect');
Route::get('/merchant/cloudflare/callback', [CloudflareDomainConnectController::class, 'callback'])
    ->name('merchant.cloudflare.callback');

// Central Marketing Routes (Blog)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// XML Sitemap for Search Engines
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Robots.txt for Search Engines
Route::get('/robots.txt', function (Request $request) {
    $host = $request->getHost();
    if (in_array($host, config('tenancy.central_domains', []))) {
        return response("User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /merchant\nDisallow: /api\nDisallow: /billing/callback/\nDisallow: /impersonate/\n\nSitemap: ".url('/sitemap.xml')."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    return app(Pipeline::class)
        ->send($request)
        ->through([
            InitializeTenancyByDomain::class,
            CheckTenantAccess::class,
        ])
        ->then(function () {
            $store = tenant();
            $sitemapUrl = $store ? rtrim($store->getStoreUrl(), '/').'/sitemap.xml' : url('/sitemap.xml');
            $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /merchant\nDisallow: /api\nDisallow: /dashboard\nDisallow: /user/\n\nSitemap: {$sitemapUrl}\n";

            return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
        });
})->name('robots');

// Central Domain Policy Routes (Parameterized domain avoids duplicate route names during route:cache)
$centralDomains = config('tenancy.central_domains', []);
if (! empty($centralDomains)) {
    Route::domain('{central_domain}')
        ->whereIn('central_domain', $centralDomains)
        ->group(function () {
            Route::get('/privacy-policy', function () {
                return Inertia::render('Storefront/PrivacyPolicy');
            })->name('central.privacy-policy');
            Route::redirect('/privacy', '/privacy-policy');
        });
}
