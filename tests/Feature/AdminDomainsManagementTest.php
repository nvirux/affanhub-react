<?php

use App\Filament\Resources\Domains\Pages\ListDomains;
use App\Models\Admin;
use App\Models\Domain;
use App\Models\Owner;
use App\Models\Store;
use App\Services\Communication\MerchantOutreachService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('central admin can view domains list with tabs for subdomains and custom domains', function () {
    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $owner = Owner::create([
        'name' => 'Domain Merchant',
        'email' => 'merchant@example.com',
        'phone' => '08099887766',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Crown Telecom',
        'public_id' => 'str_crown',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    // 1. Subdomain (platform)
    $subdomain = Domain::create([
        'tenant_id' => $store->id,
        'domain' => 'crown.localhost',
        'is_primary' => true,
        'is_routing_enabled' => true,
        'status' => 'active',
    ]);

    // 2. Custom Domain
    $customDomain = Domain::create([
        'tenant_id' => $store->id,
        'domain' => 'crowntelecom.ng',
        'is_primary' => false,
        'is_routing_enabled' => true,
        'is_verified' => true,
        'status' => 'active',
        'verification_status' => Domain::VERIFY_ACTIVE,
    ]);

    // 3. Pending Issues Domain
    $pendingDomain = Domain::create([
        'tenant_id' => $store->id,
        'domain' => 'pendingbrand.com',
        'is_primary' => false,
        'is_routing_enabled' => false,
        'is_verified' => false,
        'status' => 'pending',
        'verification_status' => Domain::VERIFY_PENDING_DNS,
    ]);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListDomains::class)
        ->assertSuccessful()
        ->assertSee('crown.localhost')
        ->assertSee('crowntelecom.ng')
        ->assertSee('pendingbrand.com')
        ->assertSee('Crown Telecom')
        // Filter by Subdomains tab
        ->set('activeTab', 'subdomains')
        ->assertSee('crown.localhost')
        ->assertDontSee('crowntelecom.ng')
        // Filter by Custom Domains tab
        ->set('activeTab', 'custom')
        ->assertSee('crowntelecom.ng')
        ->assertSee('pendingbrand.com')
        ->assertDontSee('crown.localhost')
        // Filter by Issues tab
        ->set('activeTab', 'issues')
        ->assertSee('pendingbrand.com')
        ->assertDontSee('crown.localhost');
});

test('central admin can approve and enable custom domain routing', function () {
    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $owner = Owner::create([
        'name' => 'Domain Merchant',
        'email' => 'merchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Beta Telecom',
        'public_id' => 'str_beta',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    $domain = Domain::create([
        'tenant_id' => $store->id,
        'domain' => 'betatelecom.com',
        'is_routing_enabled' => false,
        'is_approved' => false,
    ]);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListDomains::class)
        ->callTableAction('approve_routing', $domain)
        ->assertHasNoTableActionErrors();

    expect($domain->fresh()->is_routing_enabled)->toBeTrue();
    expect($domain->fresh()->is_approved)->toBeTrue();
});

test('central admin can toggle primary domain for a store', function () {
    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $owner = Owner::create([
        'name' => 'Domain Merchant',
        'email' => 'merchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Gamma Telecom',
        'public_id' => 'str_gamma',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    $oldPrimary = Domain::create([
        'tenant_id' => $store->id,
        'domain' => 'gamma.localhost',
        'is_primary' => true,
    ]);

    $newPrimary = Domain::create([
        'tenant_id' => $store->id,
        'domain' => 'gammadata.com',
        'is_primary' => false,
    ]);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListDomains::class)
        ->callTableAction('make_primary', $newPrimary)
        ->assertHasNoTableActionErrors();

    expect($newPrimary->fresh()->is_primary)->toBeTrue();
    expect($oldPrimary->fresh()->is_primary)->toBeFalse();
});

test('merchant outreach service provides domain setup templates', function () {
    $templates = MerchantOutreachService::getDomainTemplates('Aliyu Sani', 'datahub.com', 'Data Hub Store');

    expect($templates)->toHaveKey('dns_instructions')
        ->and($templates)->toHaveKey('domain_approved')
        ->and($templates)->toHaveKey('dns_issue');

    expect($templates['dns_instructions']['message'])->toContain('datahub.com');
    expect($templates['dns_instructions']['message'])->toContain('cname.affanhub.com');
});
