<?php

use App\Http\Controllers\BvnVerificationController;
use App\Http\Controllers\ModificationController;
use App\Http\Controllers\NinVerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'transaction_pin'])->group(function () {
    // NIN Verification & Slip Search
    Route::get('/identity/nin', [NinVerificationController::class, 'index'])->name('identity.nin');
    Route::post('/identity/nin/verify', [NinVerificationController::class, 'verify'])->name('identity.nin.verify');

    // BVN Verification & Slip Search
    Route::get('/identity/bvn', [BvnVerificationController::class, 'index'])->name('identity.bvn');
    Route::post('/identity/bvn/verify', [BvnVerificationController::class, 'verify'])->name('identity.bvn.verify');

    // Identity Modifications (Separated NIN & BVN routes)
    Route::get('/identity/nin-modification', [ModificationController::class, 'ninIndex'])->name('identity.nin-modification');
    Route::get('/identity/bvn-modification', [ModificationController::class, 'bvnIndex'])->name('identity.bvn-modification');
    Route::get('/identity/modification', [ModificationController::class, 'index'])->name('identity.modification');
    Route::post('/identity/modification', [ModificationController::class, 'store'])->name('identity.modification.store');
    Route::get('/identity/modification/{reference}/download', [ModificationController::class, 'downloadResult'])->name('identity.modification.download');

    // View Verification Record & Slip Details
    Route::get('/identity/verifications/{reference}', [NinVerificationController::class, 'show'])->name('identity.verifications.show');

    // Download Verification Slip
    Route::get('/identity/verifications/{reference}/download-slip', [NinVerificationController::class, 'downloadSlip'])->name('identity.verifications.download-slip');
});
