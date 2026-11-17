<?php

use App\Http\Controllers\CacController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'transaction_pin'])->group(function () {
    // CAC Storefront Index & Application
    Route::get('/cac', [CacController::class, 'index'])->name('cac.index');
    Route::post('/cac', [CacController::class, 'store'])->name('cac.store');

    // Download Official Issued CAC Certificate or Status Report
    Route::get('/cac/{reference}/download-certificate', [CacController::class, 'downloadCertificate'])->name('cac.download-certificate');
});
