<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Models\VirtualAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@security-test.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'PayMint Secured Store',
        'owner_id' => $this->owner->id,
    ]);

    $this->user = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    $this->virtualAccount = VirtualAccount::create([
        'holder_type' => User::class,
        'holder_id' => $this->user->id,
        'account_number' => '9988776655',
        'account_name' => 'Security Test Account',
        'bank_name' => 'PalmPay',
        'provider' => 'paymint',
        'reference' => 'VA_TEST_SEC_1',
    ]);
});

test('paymint webhook rejects requests with 500 if secret key is not configured', function () {
    Config::set('paymint.secret_key', null);
    Config::set('services.paymint.secret_key', null);
    putenv('PAYMINT_SECRET_KEY=');

    $payload = [
        'event' => 'payment.success',
        'status' => 'successful',
        'data' => [
            'amount' => 1000,
            'reference' => 'TX_SECRET_TEST_1',
            'receiver' => ['account_number' => '9988776655'],
        ],
    ];

    $response = $this->postJson(route('webhooks.paymint'), $payload);

    $response->assertStatus(500);
    $response->assertJson(['error' => 'Webhook secret configuration missing.']);
});

test('paymint webhook rejects requests if signature header is missing', function () {
    Config::set('paymint.secret_key', 'super-secret-key-12345');

    $payload = [
        'event' => 'payment.success',
        'status' => 'successful',
        'data' => [
            'amount' => 1000,
            'reference' => 'TX_MISSING_SIG_1',
            'receiver' => ['account_number' => '9988776655'],
        ],
    ];

    $response = $this->postJson(route('webhooks.paymint'), $payload);

    $response->assertStatus(401);
    $response->assertJson(['error' => 'Missing signature header.']);
});

test('paymint webhook rejects requests with invalid signature', function () {
    Config::set('paymint.secret_key', 'super-secret-key-12345');

    $payload = [
        'event' => 'payment.success',
        'status' => 'successful',
        'data' => [
            'amount' => 1000,
            'reference' => 'TX_INVALID_SIG_1',
            'receiver' => ['account_number' => '9988776655'],
        ],
    ];

    $response = $this->postJson(route('webhooks.paymint'), $payload, [
        'X-Paymint-Signature' => 'invalid-tampered-signature',
    ]);

    $response->assertStatus(401);
    $response->assertJson(['error' => 'Invalid signature.']);
});

test('paymint webhook accepts and credits wallet with valid SHA512 signature', function () {
    $secret = 'super-secret-key-12345';
    Config::set('paymint.secret_key', $secret);
    Config::set('services.paymint.secret_key', $secret);

    $payload = [
        'event' => 'payment.success',
        'status' => 'successful',
        'data' => [
            'amount' => 1000,
            'reference' => 'TX_VALID_SIG_1',
            'receiver' => ['account_number' => '9988776655'],
        ],
    ];

    $rawContent = json_encode($payload);
    $validSignature = hash_hmac('sha512', $rawContent, $secret);

    $response = $this->call(
        'POST',
        route('webhooks.paymint'),
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X-PAYMINT-SIGNATURE' => $validSignature,
        ],
        $rawContent
    );

    $response->assertStatus(200);
    $response->assertJson(['status' => 'success']);
});
