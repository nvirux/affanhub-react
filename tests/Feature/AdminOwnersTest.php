<?php

use App\Filament\Resources\Owners\Pages\ListOwners;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('central admin can view owners list with tabs for owners and staff', function () {
    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    // 1. Create a Store Owner
    $ownerUser = Owner::create([
        'name' => 'Store Founder',
        'email' => 'founder@example.com',
        'password' => bcrypt('password'),
        'max_stores' => 3,
    ]);

    Store::create([
        'name' => 'Founder Store',
        'public_id' => 'str_founder',
        'owner_id' => $ownerUser->id,
        'status' => 'active',
    ]);

    // 2. Create a Staff Member (no owned stores, but assigned to a store)
    $staffUser = Owner::create([
        'name' => 'Store Staff',
        'email' => 'staff@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListOwners::class)
        ->assertSuccessful()
        ->assertSee('Store Founder')
        ->assertSee('Store Staff')
        // Filter by Store Owners tab
        ->set('activeTab', 'owners')
        ->assertSee('Store Founder')
        ->assertDontSee('Store Staff')
        // Filter by Staff & Managers tab
        ->set('activeTab', 'staff')
        ->assertSee('Store Staff')
        ->assertDontSee('Store Founder');
});
