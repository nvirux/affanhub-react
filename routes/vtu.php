<?php

use App\Http\Controllers\AirtimeCashController;
use App\Http\Controllers\AirtimeController;
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'transaction_pin'])->group(function () {
    // Airtime Routes
    Route::get('/vtu/airtime', [AirtimeController::class, 'index'])->name('vtu.airtime');
    Route::post('/vtu/airtime/purchase', [AirtimeController::class, 'purchase'])->name('vtu.airtime.purchase');

    // Data Routes
    Route::get('/vtu/data', [DataController::class, 'index'])->name('vtu.data');
    Route::get('/vtu/data/plans', [DataController::class, 'getPlans'])->name('vtu.data.plans');
    Route::post('/vtu/data/purchase', [DataController::class, 'purchase'])->name('vtu.data.purchase');

    // Airtime to Cash Routes
    Route::get('/vtu/airtime-to-cash', [AirtimeCashController::class, 'index'])->name('vtu.airtime-to-cash');
    Route::get('/vtu/airtime_cash', fn () => redirect()->route('vtu.airtime-to-cash'));
    Route::get('/vtu/airtime-cash', fn () => redirect()->route('vtu.airtime-to-cash'));
    Route::post('/vtu/airtime-to-cash/submit', [AirtimeCashController::class, 'submit'])->name('vtu.airtime-to-cash.submit');
});
