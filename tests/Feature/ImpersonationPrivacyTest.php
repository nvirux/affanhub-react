<?php

use App\Http\Controllers\ImpersonationController;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);

test('impersonation does not expose admin events to merchant store activity logs', function () {
    $owner = Owner::create([
        'name' => 'Demo Merchant',
        'email' => 'merchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Demo Storefront',
        'public_id' => 'str_demo',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    $admin = Admin::create([
        'name' => 'AffanHub Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    // Simulate impersonation end
    session()->put('impersonated_by_admin', $admin->id);
    session()->put('impersonated_store_id', $store->id);
    session()->put('impersonate_anonymous', false);

    $controller = new ImpersonationController;
    $request = Request::create('/impersonate/leave', 'GET');
    $controller->leave($request);

    // Verify activity log was created for central admin audit with store relation
    $log = ActivityLog::where('event', 'admin_impersonate_store_end')->first();
    expect($log)->not->toBeNull()
        ->and($log->tenant_id)->toBe($store->id)
        ->and($log->tenant->name)->toBe('Demo Storefront');

    // Verify merchant store activity logs query strictly excludes impersonation events
    $storeLogsCount = ActivityLog::where('tenant_id', $store->id)
        ->where('event', 'not like', 'admin_impersonate_%')
        ->count();

    expect($storeLogsCount)->toBe(0);
});

test('anonymous impersonation mode creates zero activity logs', function () {
    $admin = Admin::create([
        'name' => 'AffanHub Admin',
        'email' => 'admin2@example.com',
        'password' => bcrypt('password'),
    ]);

    $owner = Owner::create([
        'name' => 'Demo Merchant 2',
        'email' => 'merchant2@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Demo Storefront 2',
        'public_id' => 'str_demo2',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    session()->put('impersonated_by_admin', $admin->id);
    session()->put('impersonated_store_id', $store->id);
    session()->put('impersonate_anonymous', true);

    $controller = new ImpersonationController;
    $request = Request::create('/impersonate/leave', 'GET');
    $controller->leave($request);

    expect(ActivityLog::where('event', 'admin_impersonate_store_end')->count())->toBe(0);
});
