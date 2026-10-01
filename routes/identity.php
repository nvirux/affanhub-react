<?php

use App\Http\Controllers\BvnVerificationController;
use App\Http\Controllers\NinVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'transaction_pin'])->group(function () {
    // NIN Verification & Slip Search
    Route::get('/identity/nin', [NinVerificationController::class, 'index'])->name('identity.nin');
    Route::post('/identity/nin/verify', [NinVerificationController::class, 'verify'])->name('identity.nin.verify');

    // BVN Verification & Slip Search
    Route::get('/identity/bvn', [BvnVerificationController::class, 'index'])->name('identity.bvn');
    Route::post('/identity/bvn/verify', [BvnVerificationController::class, 'verify'])->name('identity.bvn.verify');
});
