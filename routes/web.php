<?php

use App\Http\Controllers\Auth\MerchantGoogleAuthController;
use App\Http\Controllers\Auth\MerchantPhoneCompletionController;
use App\Http\Controllers\BillingCheckoutCallbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\Merchant\StaffInvitationController;
use App\Http\Controllers\MobileAppDownloadController;
use Illuminate\Support\Facades\Route;

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
