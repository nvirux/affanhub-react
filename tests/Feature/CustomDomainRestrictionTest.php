<?php

use App\Models\Feature;
use App\Models\Owner;
use App\Models\Store;
use App\Services\Cloudflare\CloudflareSaaSService;
use App\Services\Domain\CustomDomainOnboardingService;
use App\Services\Domain\DnsVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cannot add central platform domain or any subdomain of affanhub or localhost as custom domain', function () {
    Feature::create([
        'name' => 'Custom Domain',
        'slug' => 'custom_domain',
        'type' => 'boolean',
        'default_value' => 'true',
    ]);

    $owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Demo Store',
        'public_id' => 'str_demo_domain',
        'owner_id' => $owner->id,
        'status' => 'active',
        'plan_features' => ['custom_domain' => true],
    ]);

    $dnsMock = mock(DnsVerificationService::class);
    $dnsMock->shouldReceive('normalizeDomain')->andReturnUsing(fn ($d) => strtolower(trim($d)));
    $cloudflareMock = mock(CloudflareSaaSService::class);

    $service = new CustomDomainOnboardingService($dnsMock, $cloudflareMock);

    // Testing affanhub.com
    expect(fn () => $service->submitDomain($store, 'affanhub.com'))
        ->toThrow(Exception::class, 'You cannot connect an AffanHub platform domain or subdomain');

    // Testing demo2.affanhub.com
    expect(fn () => $service->submitDomain($store, 'demo2.affanhub.com'))
        ->toThrow(Exception::class, 'You cannot connect an AffanHub platform domain or subdomain');

    // Testing sub.demo2.affanhub.com
    expect(fn () => $service->submitDomain($store, 'sub.demo2.affanhub.com'))
        ->toThrow(Exception::class, 'You cannot connect an AffanHub platform domain or subdomain');

    // Testing localhost and subdomains
    expect(fn () => $service->submitDomain($store, 'merchant.localhost'))
        ->toThrow(Exception::class, 'You cannot connect an AffanHub platform domain or subdomain');
});
