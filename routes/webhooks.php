<?php

use App\Http\Controllers\Webhook\PayMintWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/paymint', [PayMintWebhookController::class, 'handle'])->name('webhooks.paymint');
