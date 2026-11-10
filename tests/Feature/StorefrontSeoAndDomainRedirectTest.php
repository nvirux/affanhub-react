<?php

use App\Filament\Merchant\Pages\StoreSettings;
use App\Models\Domain;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'SEO Merchant',
        'email' => 'seomerchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Adeola Telecom',
        'owner_id' => $this->owner->id,
        'status' => 'active',
        'description' => 'Best platform to buy cheap data and airtime in Nigeria.',
        'dashboard_subtitle' => 'Fast, Automated & Instant',
        'google_site_verification' => 'google12345abcdef',
    ]);

    // Subdomain
    Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'adeola.localhost',
        'is_primary' => false,
    ]);
});

test('tenant homepage renders SEO meta tags, title, and google search console verification', function () {
    $response = $this->get('http://adeola.localhost/');

    $response->assertStatus(200);
    $content = $response->getContent();

    expect($content)
        ->toContain('Adeola Telecom')
        ->toContain('<meta name="robots" content="index, follow">')
        ->toContain('<meta name="google-site-verification" content="google12345abcdef">')
        ->toContain('<meta name="description" content="Best platform to buy cheap data and airtime in Nigeria.">')
        ->toContain('rel="canonical" href="http://adeola.localhost');
});

test('when merchant has an active healthy primary custom domain, subdomain 301 redirects to custom domain', function () {
    // Pro plan with custom domain feature
    $plan = Plan::create([
        'name' => 'Pro Plan',
        'slug' => 'pro',
        'price_monthly' => 4999,
        'features' => ['custom_domain'],
    ]);
    $this->store->update(['plan_id' => $plan->id]);

    // Custom domain
    Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'adeoladata.com',
        'is_primary' => true,
        'is_verified' => true,
        'is_routing_enabled' => true,
        'status' => 'verified',
        'verification_status' => Domain::VERIFY_ACTIVE,
    ]);

    // Visiting subdomain on homepage
    $response = $this->get('http://adeola.localhost/');
    $response->assertStatus(301);
    $response->assertRedirect('http://adeoladata.com/');

    // Visiting subdomain on inner route
    $innerResponse = $this->get('http://adeola.localhost/login');
    $innerResponse->assertStatus(301);
    $innerResponse->assertRedirect('http://adeoladata.com/login');
});

test('unverified custom domain does not trigger redirect and store remains accessible on subdomain', function () {
    Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'unverified-custom.com',
        'is_primary' => true,
        'is_verified' => false,
        'is_routing_enabled' => false,
        'status' => 'pending',
    ]);

    $response = $this->get('http://adeola.localhost/');
    $response->assertStatus(200);
});

test('storefront sitemap.xml returns valid xml sitemap with tenant URLs', function () {
    $response = $this->get('http://adeola.localhost/sitemap.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

    $content = $response->getContent();
    expect($content)
        ->toContain('<?xml version="1.0" encoding="UTF-8"?>')
        ->toContain('<urlset')
        ->toContain('adeola.localhost/')
        ->toContain('adeola.localhost/login')
        ->toContain('adeola.localhost/register')
        ->toContain('adeola.localhost/contact')
        ->toContain('adeola.localhost/privacy-policy');
});

test('storefront robots.txt returns robots directives pointing to store sitemap', function () {
    $response = $this->get('http://adeola.localhost/robots.txt');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');

    $content = $response->getContent();
    expect($content)
        ->toContain('User-agent: *')
        ->toContain('Allow: /')
        ->toContain('Disallow: /dashboard')
        ->toContain('Sitemap: http://adeola.localhost/sitemap.xml');
});

test('merchant can save google search console verification tag in store settings and it sanitizes meta tag', function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    Livewire::test(StoreSettings::class)
        ->set('name', 'Adeola Telecom Global')
        ->set('googleSiteVerification', '<meta name="google-site-verification" content="google-verified-token-999" />')
        ->call('saveSettings')
        ->assertHasNoErrors();

    $this->store->refresh();
    expect($this->store->google_site_verification)->toBe('google-verified-token-999');
});

test('visiting /admin on storefront redirects to merchant panel on both subdomains and custom domains', function () {
    // 1. Subdomain
    $this->get('http://adeola.localhost/admin')
        ->assertRedirect(route('filament.merchant.tenant'));

    $this->get('http://adeola.localhost/admin/login')
        ->assertRedirect(route('filament.merchant.tenant'));

    // 2. Custom Domain
    Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'mycustomstore.com',
        'is_primary' => true,
        'is_verified' => true,
        'is_routing_enabled' => true,
        'status' => 'verified',
        'verification_status' => Domain::VERIFY_ACTIVE,
    ]);

    $this->get('http://mycustomstore.com/admin')
        ->assertRedirect(route('filament.merchant.tenant'));

    $this->get('http://mycustomstore.com/admin/login')
        ->assertRedirect(route('filament.merchant.tenant'));
});
