<?php

use App\Http\Controllers\BillingCheckoutCallbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpersonationController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

// Impersonation Routes
Route::get('/impersonate/consume', [ImpersonationController::class, 'consume'])->name('impersonate.consume');
Route::get('/impersonate/leave', [ImpersonationController::class, 'leave'])->name('impersonate.leave');

// PayMint Checkout Callback
Route::get('/billing/callback/{tenant:public_id}', [BillingCheckoutCallbackController::class, 'handle'])
    ->name('merchant.billing.callback');

// Staff Invitation Acceptance Routes
use App\Http\Controllers\Merchant\StaffInvitationController;

Route::get('/invitations/{token}', [StaffInvitationController::class, 'show'])->name('merchant.invitation.show');
Route::post('/invitations/{token}/accept', [StaffInvitationController::class, 'acceptExisting'])->name('merchant.invitation.accept-existing');
Route::post('/invitations/{token}/register', [StaffInvitationController::class, 'registerAndAccept'])->name('merchant.invitation.register');
