<?php

use App\Models\IdentityVerification;
use App\Models\Owner;
use App\Models\Service;
use App\Models\Slip;
use App\Models\Store;
use App\Models\User;
use App\Services\Identity\IdCoreService;
use Database\Seeders\SlipSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    config()->set('services.idcore.base_url', 'https://api.idcore.africa/v1');
    config()->set('services.idcore.api_key', 'idc_test_demo_key_123');

    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'storeowner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Demo Telecom',
        'owner_id' => $this->owner->id,
    ]);

    DB::table('domains')->insert([
        'tenant_id' => $this->store->id,
        'domain' => 'demo.localhost',
        'is_primary' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->user = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
    ]);

    $this->seed(SlipSeeder::class);
});

test('IdCoreService sends Authorization Bearer header and correct payload for NIN lookup', function () {
    Http::fake([
        'https://api.idcore.africa/v1/verifications/nin' => Http::response([
            'status' => 'success',
            'reference' => 'VRF-NIN-API-TEST999',
            'service' => 'nin',
            'tier' => 'advance',
            'amount_charged' => 150.00,
            'slip' => [
                'slug' => 'nin-premium',
                'name' => 'Premium NIMC Card',
                'download_url' => 'https://api.idcore.africa/v1/verifications/VRF-NIN-API-TEST999/slip',
            ],
            'data' => [
                'nin' => '12345678901',
                'first_name' => 'AHMAD',
                'last_name' => 'SULAIMAN',
                'full_name' => 'AHMAD SULAIMAN',
                'date_of_birth' => '1992-05-10',
                'gender' => 'Male',
                'tracking_id' => 'TRK123456',
            ],
        ], 200),
    ]);

    $service = new IdCoreService;
    $result = $service->verifyNin('nin', '12345678901', 'premium', 'advance');

    expect($result['success'])->toBeTrue()
        ->and($result['reference'])->toBe('VRF-NIN-API-TEST999')
        ->and($result['data']['first_name'])->toBe('AHMAD');

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Bearer idc_test_demo_key_123')
            && $request->url() === 'https://api.idcore.africa/v1/verifications/nin'
            && $request['nin'] === '12345678901'
            && $request['slip_slug'] === 'nin-premium';
    });
});

test('IdCoreService sends Authorization Bearer header for BVN lookup', function () {
    Http::fake([
        'https://api.idcore.africa/v1/verifications/bvn' => Http::response([
            'status' => 'success',
            'reference' => 'VRF-BVN-API-TEST888',
            'service' => 'bvn',
            'tier' => 'advance',
            'amount_charged' => 200.00,
            'slip' => [
                'slug' => 'bvn-plastic',
                'name' => 'Plastic BVN Card',
            ],
            'data' => [
                'bvn' => '22280005737',
                'first_name' => 'ZAINAB',
                'last_name' => 'ALIYU',
                'full_name' => 'ZAINAB ALIYU',
                'enrollment_bank' => 'Access Bank',
            ],
        ], 200),
    ]);

    $service = new IdCoreService;
    $result = $service->verifyBvn('22280005737', 'plastic', 'advance');

    expect($result['success'])->toBeTrue()
        ->and($result['reference'])->toBe('VRF-BVN-API-TEST888');

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization', 'Bearer idc_test_demo_key_123')
            && $request->url() === 'https://api.idcore.africa/v1/verifications/bvn'
            && $request['bvn'] === '22280005737'
            && $request['slip_slug'] === 'bvn-plastic';
    });
});

test('authenticated user can download their verification slip PDF', function () {
    $ninService = Service::where('key', 'nin_verification')->first();
    $slip = Slip::where('service_id', $ninService->id)->where('slug', 'standard')->first();

    $verification = IdentityVerification::create([
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'service_id' => $ninService->id,
        'slip_id' => $slip->id,
        'search_type' => 'nin',
        'search_value' => '12345678901',
        'reference' => 'NIN-TEST-DOWNLOAD-REF',
        'provider_reference' => 'VRF-NIN-API-TEST999',
        'status' => 'successful',
        'fee_charged' => 150.00,
    ]);

    Http::fake([
        'https://api.idcore.africa/v1/verifications/VRF-NIN-API-TEST999/slip' => Http::response('%PDF-1.4 Mock PDF Content', 200, [
            'Content-Type' => 'application/pdf',
        ]),
    ]);

    $this->actingAs($this->user);

    $response = $this->get('http://demo.localhost/identity/verifications/NIN-TEST-DOWNLOAD-REF/download-slip');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
    expect($response->getContent())->toBe('%PDF-1.4 Mock PDF Content');
});

test('unauthorized user cannot download someone elses slip', function () {
    $ninService = Service::where('key', 'nin_verification')->first();

    $verification = IdentityVerification::create([
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'service_id' => $ninService->id,
        'search_type' => 'nin',
        'search_value' => '12345678901',
        'reference' => 'NIN-PRIVATE-REF',
        'provider_reference' => 'VRF-NIN-API-PRIVATE',
        'status' => 'successful',
        'fee_charged' => 150.00,
    ]);

    $otherUser = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
        'email_verified_at' => now(),
    ]);

    $this->actingAs($otherUser);

    $response = $this->get('http://demo.localhost/identity/verifications/NIN-PRIVATE-REF/download-slip');
    $response->assertForbidden();
});

test('user can view their dedicated verification record page', function () {
    $ninService = Service::where('key', 'nin_verification')->first();
    $slip = Slip::where('service_id', $ninService->id)->where('slug', 'standard')->first();

    $verification = IdentityVerification::create([
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'service_id' => $ninService->id,
        'slip_id' => $slip->id,
        'search_type' => 'nin',
        'search_value' => '12345678901',
        'reference' => 'NIN-VIEW-PAGE-TEST',
        'provider_reference' => 'VRF-NIN-API-TEST999',
        'status' => 'successful',
        'recipient_name' => 'AHMAD SULAIMAN',
        'tracking_id' => 'TRK123456',
        'fee_charged' => 150.00,
        'data_payload' => [
            'nin' => '12345678901',
            'full_name' => 'AHMAD SULAIMAN',
            'dob' => '1992-05-10',
            'gender' => 'Male',
        ],
    ]);

    $this->actingAs($this->user);

    $response = $this->get('http://demo.localhost/identity/verifications/NIN-VIEW-PAGE-TEST');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Identity/Show')
        ->where('verification.reference', 'NIN-VIEW-PAGE-TEST')
        ->where('verification.recipient_name', 'AHMAD SULAIMAN')
        ->has('verification.preview_url')
        ->has('verification.download_url')
    );
});

test('unauthorized user cannot view someone elses verification record page', function () {
    $ninService = Service::where('key', 'nin_verification')->first();

    $verification = IdentityVerification::create([
        'store_id' => $this->store->id,
        'user_id' => $this->user->id,
        'service_id' => $ninService->id,
        'search_type' => 'nin',
        'search_value' => '12345678901',
        'reference' => 'NIN-SECRET-VIEW-TEST',
        'status' => 'successful',
        'fee_charged' => 150.00,
    ]);

    $otherUser = User::factory()->withTransactionPin('1234')->create([
        'store_id' => $this->store->id,
        'email_verified_at' => now(),
    ]);

    $this->actingAs($otherUser);

    $response = $this->get('http://demo.localhost/identity/verifications/NIN-SECRET-VIEW-TEST');
    $response->assertForbidden();
});
