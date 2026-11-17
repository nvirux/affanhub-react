<?php

use App\Http\Controllers\CacController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'transaction_pin'])->group(function () {
    // CAC Storefront Index & Application
    Route::get('/cac', [CacController::class, 'index'])->name('cac.index');
    Route::get('/cac-services', [CacController::class, 'index'])->name('cac-services.index');
    Route::get('/identity/cac', [CacController::class, 'index'])->name('identity.cac.index');
    Route::get('/identity/cac-services', [CacController::class, 'index'])->name('identity.cac-services.index');
    Route::post('/cac', [CacController::class, 'store'])->name('cac.store');

    // Download Official Issued CAC Certificate or Status Report
    Route::get('/cac/{reference}/download-certificate', [CacController::class, 'downloadCertificate'])->name('cac.download-certificate');
});
