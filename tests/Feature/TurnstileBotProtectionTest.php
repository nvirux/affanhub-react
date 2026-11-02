<?php

use App\Rules\TurnstileRule;
use App\Services\Security\TurnstileService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

test('turnstile service verifies token successfully with cloudflare siteverify', function () {
    Http::fake([
        TurnstileService::SITEVERIFY_URL => Http::response([
            'success' => true,
            'challenge_ts' => now()->toISOString(),
            'hostname' => 'merchant.affanhub.com',
            'action' => 'merchant_auth',
        ], 200),
    ]);

    config(['services.turnstile.secret_key' => '0x4AAAAAAFv4Lt9HiCu7N3WjgKZBz7PfCD0']);

    // Create service without testing bypass
    app()->detectEnvironment(fn () => 'production');
    $service = new TurnstileService;

    $result = $service->verify('valid_dummy_token', '127.0.0.1', 'merchant_auth');
    expect($result)->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === TurnstileService::SITEVERIFY_URL
            && $request['secret'] === '0x4AAAAAAFv4Lt9HiCu7N3WjgKZBz7PfCD0'
            && $request['response'] === 'valid_dummy_token'
            && $request['remoteip'] === '127.0.0.1';
    });
});

test('turnstile service rejects invalid or failed tokens from cloudflare', function () {
    Http::fake([
        TurnstileService::SITEVERIFY_URL => Http::response([
            'success' => false,
            'error-codes' => ['invalid-input-response'],
        ], 200),
    ]);

    config(['services.turnstile.secret_key' => '0x4AAAAAAFv4Lt9HiCu7N3WjgKZBz7PfCD0']);

    app()->detectEnvironment(fn () => 'production');
    $service = new TurnstileService;

    $result = $service->verify('invalid_token', '127.0.0.1', 'merchant_auth');
    expect($result)->toBeFalse();
});

test('turnstile service rejects mismatched actions', function () {
    Http::fake([
        TurnstileService::SITEVERIFY_URL => Http::response([
            'success' => true,
            'action' => 'wrong_action',
        ], 200),
    ]);

    config(['services.turnstile.secret_key' => '0x4AAAAAAFv4Lt9HiCu7N3WjgKZBz7PfCD0']);

    app()->detectEnvironment(fn () => 'production');
    $service = new TurnstileService;

    $result = $service->verify('valid_token', '127.0.0.1', 'merchant_auth');
    expect($result)->toBeFalse();
});

test('turnstile validation rule rejects empty or missing tokens', function () {
    app()->detectEnvironment(fn () => 'production');
    config(['services.turnstile.force_testing' => true]);

    $validator = Validator::make(
        ['turnstile_token' => ''],
        ['turnstile_token' => [new TurnstileRule('merchant_auth')]]
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('turnstile_token'))->toContain('security check');
});
