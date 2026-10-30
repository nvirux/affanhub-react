<?php

use App\Http\Middleware\EnsureTransactionPinSet;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Models\Domain;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->group(base_path('routes/webhooks.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            EnsureUserIsActive::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'transaction_pin' => EnsureTransactionPinSet::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (TenantCouldNotBeIdentifiedOnDomainException $e, Request $request) {
            $scheme = $request->getScheme();
            $host = $request->getHost();
            $port = $request->getPort();
            $portString = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';

            // Automatically redirect www <-> non-www if the counterpart is a registered store domain
            if (str_starts_with($host, 'www.')) {
                $nonWwwHost = substr($host, 4);
                if (Domain::where('domain', $nonWwwHost)->exists()) {
                    return redirect()->to("{$scheme}://{$nonWwwHost}{$portString}".$request->getRequestUri(), 301);
                }
            } else {
                $wwwHost = 'www.'.$host;
                if (Domain::where('domain', $wwwHost)->exists()) {
                    return redirect()->to("{$scheme}://{$wwwHost}{$portString}".$request->getRequestUri(), 301);
                }
            }

            $appHost = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
            if (str_contains($host, 'localhost') || $host === '127.0.0.1') {
                $centralUrl = "{$scheme}://localhost{$portString}";
                $merchantRegisterUrl = "{$scheme}://merchant.localhost{$portString}/register";
                $merchantLoginUrl = "{$scheme}://merchant.localhost{$portString}/login";
            } else {
                $centralUrl = "{$scheme}://{$appHost}{$portString}";
                $merchantRegisterUrl = "{$scheme}://merchant.{$appHost}{$portString}/register";
                $merchantLoginUrl = "{$scheme}://merchant.{$appHost}{$portString}/login";
            }

            return response()->view('errors.store-not-found', [
                'domain' => $host,
                'centralUrl' => $centralUrl,
                'merchantRegisterUrl' => $merchantRegisterUrl,
                'merchantLoginUrl' => $merchantLoginUrl,
            ], 404);
        });
    })->create();
