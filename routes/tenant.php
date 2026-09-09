<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\ForgotPinController;
use App\Http\Controllers\Auth\TenantAuthController;
use App\Http\Controllers\Auth\TenantPinSetupController;
use App\Http\Controllers\Auth\TransactionPinController;
use App\Http\Controllers\EarnController;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

use App\Http\Controllers\VirtualAccountController;
use App\Http\Controllers\WalletController;
use App\Http\Middleware\CheckTenantAccess;
use App\Models\Service;
use App\Models\StoreService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    CheckTenantAccess::class,
])->group(function () {

    // Make authentication routes tenant-aware!
    Route::post('/login/check-identifier', [TenantAuthController::class, 'checkIdentifier'])
        ->name('login.check-identifier')
        ->middleware('throttle:30,1');

    // Forgot / Reset PIN routes
    Route::middleware(['guest'])->group(function () {
        Route::get('/forgot-pin', [ForgotPinController::class, 'create'])->name('pin.request');
        Route::post('/forgot-pin', [ForgotPinController::class, 'store'])->name('pin.email');
        Route::get('/reset-pin/{token}', [ForgotPinController::class, 'edit'])->name('pin.reset');
        Route::post('/reset-pin', [ForgotPinController::class, 'update'])->name('pin.update');
    });

    Route::group([
        'namespace' => 'Laravel\Fortify\Http\Controllers',
    ], function () {
        require base_path('vendor/laravel/fortify/routes/routes.php');
    });

    // PIN Setup for legacy password users
    Route::middleware(['auth'])->group(function () {
        Route::get('/setup-pin', [TenantPinSetupController::class, 'show'])->name('pin.setup');
        Route::post('/setup-pin', [TenantPinSetupController::class, 'store'])->name('pin.setup.store');

        // Transaction PIN Setup
        Route::get('/setup-transaction-pin', [TransactionPinController::class, 'create'])->name('transaction-pin.setup');
        Route::post('/setup-transaction-pin', [TransactionPinController::class, 'store'])->name('transaction-pin.setup.store');
    });

    // Customer Dashboard
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('dashboard', function () {
            $tenant = tenant();
            $allServices = Service::where('is_active', true)->get();

            $enabledServices = [];
            foreach ($allServices as $service) {
                $hasAccess = true;
                if ($service->feature_id && $service->feature) {
                    $hasAccess = $tenant->hasFeature($service->feature->slug);
                }

                $setting = StoreService::where('store_id', $tenant->id)
                    ->where('service_id', $service->id)
                    ->first();

                $isEnabled = $setting ? (bool) $setting->is_enabled : true;
                $sortOrder = $setting ? (int) $setting->sort_order : $service->sort_order;

                if ($hasAccess && $isEnabled) {
                    $enabledServices[] = [
                        'id' => $service->id,
                        'name' => $service->name,
                        'key' => $service->key,
                        'category' => $service->category,
                        'icon' => $service->icon,
                        'description' => $service->description,
                        'sort_order' => $sortOrder,
                    ];
                }
            }

            usort($enabledServices, fn ($a, $b) => $a['sort_order'] <=> $b['sort_order']);

            return Inertia::render('Storefront/Dashboard', [
                'store_services' => $enabledServices,
            ]);
        })->name('dashboard');

        Route::post('/virtual-account/generate', [VirtualAccountController::class, 'generate'])
            ->name('virtual-account.generate');

        Route::get('/earn', [EarnController::class, 'index'])->name('earn');
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');

        require __DIR__.'/vtu.php';
    });

    // Support & Contact Page
    Route::get('/contact', function () {
        return Inertia::render('Storefront/Contact');
    })->name('contact');

    // Customer Settings
    require __DIR__.'/settings.php';
});
