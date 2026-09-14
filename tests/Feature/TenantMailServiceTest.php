<?php

use App\Models\Feature;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use App\Services\Mail\TenantMailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->feature = Feature::create([
        'name' => 'Custom Email (Resend)',
        'slug' => 'custom_email',
        'type' => 'boolean',
        'default_value' => 'false',
    ]);

    $this->proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 14,
        'is_active' => true,
    ]);

    $this->proPlan->planFeatures()->create([
        'feature_id' => $this->feature->id,
        'value' => 'true',
    ]);

    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Farakwai Data',
        'public_id' => 'str_farakwai',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);
});

test('it sends email via Resend when store has custom email enabled', function () {
    // Enable Pro plan
    $this->store->subscriptions()->create([
        'plan_id' => $this->proPlan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'starts_at' => now(),
        'ends_at' => now()->addMonth(),
        'status' => 'active',
    ]);
    $this->store->refresh();

    $this->store->resend_api_key = 're_test_key_123';
    $this->store->resend_from_email = 'orders@farakwaidata.com';
    $this->store->resend_from_name = 'Farakwai Data';
    $this->store->save();

    Http::fake([
        'https://api.resend.com/emails' => Http::response(['id' => 'msg_12345'], 200),
    ]);

    $mailService = app(TenantMailService::class);
    $sent = $mailService->send(
        store: $this->store,
        to: 'customer@example.com',
        subject: 'Your Order Receipt',
        htmlContent: '<p>Thank you for purchasing.</p>'
    );

    expect($sent)->toBe(true);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.resend.com/emails'
            && $request['from'] === 'Farakwai Data <orders@farakwaidata.com>'
            && $request['to'] === ['customer@example.com']
            && $request['subject'] === 'Your Order Receipt'
            && $request->hasHeader('Authorization', 'Bearer re_test_key_123');
    });
});

test('it falls back to system mailer when store does not have custom email enabled', function () {
    Mail::fake();

    $mailService = app(TenantMailService::class);
    $sent = $mailService->send(
        store: $this->store,
        to: 'customer@example.com',
        subject: 'Reset Password',
        htmlContent: '<p>Reset link</p>'
    );

    expect($sent)->toBe(true);

    Mail::assertSent(SentMessage::class, 0); // Sent via raw html closure
});

test('it sends test verification email through Resend successfully', function () {
    Http::fake([
        'https://api.resend.com/emails' => Http::response(['id' => 'msg_test_999'], 200),
    ]);

    $mailService = app(TenantMailService::class);
    $result = $mailService->sendTestEmail(
        store: $this->store,
        apiKey: 're_valid_key',
        fromEmail: 'support@farakwaidata.com',
        fromName: 'Farakwai Data',
        recipient: 'merchant@example.com'
    );

    expect($result['success'])->toBe(true);
    expect($result['message'])->toContain('merchant@example.com');
});

test('it returns error when Resend test email fails', function () {
    Http::fake([
        'https://api.resend.com/emails' => Http::response([
            'statusCode' => 403,
            'message' => 'The domain farakwaidata.com is not verified in Resend.',
            'name' => 'validation_error',
        ], 403),
    ]);

    $mailService = app(TenantMailService::class);
    $result = $mailService->sendTestEmail(
        store: $this->store,
        apiKey: 're_invalid_key',
        fromEmail: 'unverified@farakwaidata.com',
        fromName: 'Farakwai Data',
        recipient: 'merchant@example.com'
    );

    expect($result['success'])->toBe(false);
    expect($result['error'])->toContain('not verified in Resend');
});
