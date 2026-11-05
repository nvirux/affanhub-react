<?php

use App\Filament\Resources\Stores\Pages\ListStores;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('central admin can view stores list with tabs, customer balance, and actions', function () {
    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $owner = Owner::create([
        'name' => 'Merchant Apex',
        'email' => 'apex@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Apex VTU Store',
        'public_id' => 'str_apex_vtu',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    // Customer with wallet balance
    $customer = User::create([
        'name' => 'Customer Alice',
        'email' => 'alice@example.com',
        'password' => bcrypt('password'),
        'store_id' => $store->id,
    ]);

    $customerWallet = $customer->wallet('main');
    $customerWallet->update(['balance' => 15000.00]);

    // Verify model calculation
    expect($store->totalCustomersBalance())->toBe(15000.00)
        ->and($store->customersCount())->toBe(1);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListStores::class)
        ->assertSuccessful()
        ->assertSee('Apex VTU Store')
        ->assertSee('15,000.00')
        ->set('activeTab', 'active')
        ->assertSee('Apex VTU Store')
        ->set('activeTab', 'suspended')
        ->assertDontSee('Apex VTU Store');
});

test('store helper methods accurately reflect multi-customer holdings', function () {
    $owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Multi Customer Store',
        'public_id' => 'str_multi_cust',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    $c1 = User::create([
        'name' => 'Cust 1',
        'email' => 'c1@example.com',
        'password' => bcrypt('password'),
        'store_id' => $store->id,
    ]);
    $c1->wallet('main')->update(['balance' => 5000.50]);

    $c2 = User::create([
        'name' => 'Cust 2',
        'email' => 'c2@example.com',
        'password' => bcrypt('password'),
        'store_id' => $store->id,
    ]);
    $c2->wallet('main')->update(['balance' => 7500.50]);

    expect($store->totalCustomersBalance())->toBe(12501.00)
        ->and($store->customersCount())->toBe(2);
});
