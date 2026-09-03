<?php

use App\Http\Controllers\Webhook\PayMintWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
|
| Dedicated route file for external payment & service provider webhooks.
| Loaded via bootstrap/app.php and excluded from CSRF verification.
|
*/

Route::post('/webhooks/paymint', [PayMintWebhookController::class, 'handle'])->name('webhooks.paymint');
Route::post('/api/webhooks/paymint', [PayMintWebhookController::class, 'handle']);
