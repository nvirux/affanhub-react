<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Data Routes
    Route::get('/vtu/data', [DataController::class, 'index'])->name('vtu.data');
    Route::get('/vtu/data/plans', [DataController::class, 'getPlans'])->name('vtu.data.plans');
    Route::post('/vtu/data/purchase', [DataController::class, 'purchase'])->name('vtu.data.purchase');
});
