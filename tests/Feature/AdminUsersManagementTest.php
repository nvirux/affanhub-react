<?php

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Mail\CustomerDirectMessageMail;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Services\Communication\MerchantOutreachService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('central admin can view customers list with tabs for funded, zero balance, and suspended', function () {
    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Apex Telecom',
        'public_id' => 'str_apex',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    // 1. Funded User
    $fundedUser = User::create([
        'name' => 'Funded Customer',
        'email' => 'funded@example.com',
        'phone' => '08011112222',
        'password' => bcrypt('password'),
        'store_id' => $store->id,
        'is_active' => true,
    ]);
    $fundedUser->wallet('main')->update(['balance' => 5000.00]);

    // 2. Zero Balance User
    $zeroUser = User::create([
        'name' => 'Zero Balance Customer',
        'email' => 'zero@example.com',
        'phone' => '08033334444',
        'password' => bcrypt('password'),
        'store_id' => $store->id,
        'is_active' => true,
    ]);

    // 3. Suspended User
    $suspendedUser = User::create([
        'name' => 'Suspended Customer',
        'email' => 'banned@example.com',
        'phone' => '08055556666',
        'password' => bcrypt('password'),
        'store_id' => $store->id,
        'is_active' => false,
    ]);

    // Add virtual account
    VirtualAccount::create([
        'holder_type' => User::class,
        'holder_id' => $fundedUser->id,
        'bank_name' => 'Wema Bank',
        'account_number' => '9988776655',
        'account_name' => 'Apex - Funded Customer',
        'provider' => 'monnify',
        'status' => 'active',
        'reference' => 'va_ref_001',
    ]);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListUsers::class)
        ->assertSuccessful()
        ->assertSee('Funded Customer')
        ->assertSee('Zero Balance Customer')
        ->assertSee('Suspended Customer')
        ->assertSee('Wema Bank: 9988776655')
        ->assertSee('Apex Telecom')
        // Check funded tab
        ->set('activeTab', 'funded')
        ->assertSee('Funded Customer')
        ->assertDontSee('Zero Balance Customer')
        // Check zero balance tab
        ->set('activeTab', 'zero_balance')
        ->assertSee('Zero Balance Customer')
        ->assertDontSee('Funded Customer')
        // Check suspended tab
        ->set('activeTab', 'suspended')
        ->assertSee('Suspended Customer')
        ->assertDontSee('Funded Customer');
});

test('central admin can fund customer wallet via adjust_balance action', function () {
    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $user = User::create([
        'name' => 'Test User',
        'email' => 'user@example.com',
        'phone' => '08012345678',
        'password' => bcrypt('password'),
        'is_active' => true,
    ]);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListUsers::class)
        ->callTableAction('adjust_balance', $user, [
            'action' => 'credit',
            'amount' => 1500,
            'reason' => 'Promo Bonus Credit',
        ])
        ->assertHasNoTableActionErrors();

    expect((float) $user->wallet('main')->fresh()->balance)->toBe(1500.00);
});

test('central admin can toggle ban and reactivate customer', function () {
    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $user = User::create([
        'name' => 'Active Customer',
        'email' => 'active@example.com',
        'phone' => '08099998888',
        'password' => bcrypt('password'),
        'is_active' => true,
    ]);

    $this->actingAs($admin, 'admin');

    // Suspend
    Livewire::test(ListUsers::class)
        ->callTableAction('toggle_ban', $user)
        ->assertHasNoTableActionErrors();

    expect($user->fresh()->isActive())->toBeFalse();

    // Reactivate
    Livewire::test(ListUsers::class)
        ->callTableAction('toggle_ban', $user)
        ->assertHasNoTableActionErrors();

    expect($user->fresh()->isActive())->toBeTrue();
});

test('central admin can send direct email to customer', function () {
    Mail::fake();

    $admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $user = User::create([
        'name' => 'Email Target',
        'email' => 'target@example.com',
        'phone' => '08011223344',
        'password' => bcrypt('password'),
        'is_active' => true,
    ]);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListUsers::class)
        ->callTableAction('send_email', $user, [
            'subject' => 'Important KYC Update',
            'body' => 'Please update your BVN details to continue trading seamlessly.',
        ])
        ->assertHasNoTableActionErrors();

    Mail::assertSent(CustomerDirectMessageMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email) && $mail->customSubject === 'Important KYC Update';
    });
});

test('merchant outreach service provides rich customer templates', function () {
    $templates = MerchantOutreachService::getCustomerTemplates('Ibrahim Musa', 'Affan Telecom');

    expect($templates)->toHaveKey('kyc_verification')
        ->and($templates)->toHaveKey('low_balance_topup')
        ->and($templates)->toHaveKey('support_checkin')
        ->and($templates)->toHaveKey('account_security');

    expect($templates['kyc_verification']['message'])->toContain('Ibrahim');
    expect($templates['kyc_verification']['message'])->toContain('Affan Telecom');
});
