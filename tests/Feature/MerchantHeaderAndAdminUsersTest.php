<?php

use App\Filament\Resources\Users\UserResource;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('owner remembers last active store across sessions', function () {
    $owner = Owner::create([
        'name' => 'Store Owner Test',
        'email' => 'owner_test@example.com',
        'password' => Hash::make('secret123'),
        'email_verified_at' => now(),
    ]);

    $storeA = Store::create([
        'name' => 'Alpha Telecom',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    $storeB = Store::create([
        'name' => 'Beta VTU',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    $owner->stores()->attach([$storeA->id, $storeB->id]);

    // Initial default tenant should be first store
    $panel = filament()->getPanel('merchant');
    expect($owner->getDefaultTenant($panel)->id)->toBe($storeA->id);

    // Record switch to store B
    $owner->recordActiveStore($storeB->id);
    $owner->refresh();

    expect($owner->last_active_store_id)->toBe($storeB->id);
    expect($owner->getDefaultTenant($panel)->id)->toBe($storeB->id);
});

test('admin can view enriched customers list in users resource', function () {
    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@example.com',
        'password' => Hash::make('secret123'),
        'role' => 'admin',
    ]);

    $customer = User::create([
        'name' => 'Usman Danfodio',
        'email' => 'usman@example.com',
        'phone' => '08011223344',
        'bvn' => '12345678901',
    ]);
    $customer->wallet('main')->update(['balance' => 5000.00]);

    $url = UserResource::getUrl('index');

    $response = $this->actingAs($admin, 'admin')
        ->get($url);

    $response->assertSuccessful();
    $response->assertSee('Usman Danfodio');
    $response->assertSee('₦5,000.00');
    $response->assertSee('BVN Verified');
});
