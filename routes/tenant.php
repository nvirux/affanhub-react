<?php

declare(strict_types=1);

use App\Http\Controllers\EarnController;
use App\Http\Controllers\VirtualAccountController;
use App\Http\Middleware\CheckTenantAccess;
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
    Route::group([
        'namespace' => 'Laravel\Fortify\Http\Controllers',
    ], function () {
        require base_path('vendor/laravel/fortify/routes/routes.php');
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

        require __DIR__.'/vtu.php';
    });

    // Customer Settings
    require __DIR__.'/settings.php';
});
