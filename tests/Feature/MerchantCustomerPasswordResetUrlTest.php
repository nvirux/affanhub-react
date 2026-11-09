<?php

use App\Filament\Merchant\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\Domain;
use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Notifications\TenantResetPasswordNotification;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Store Merchant',
        'email' => 'merchant@resettest.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Apex Hub Store',
        'public_id' => 'str_apexhub',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);

    $this->subdomain = Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'apexhub.localhost',
        'is_primary' => true,
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
});

test('merchant sending password reset link generates storefront url instead of merchant panel url', function () {
    Notification::fake();

    $customer = User::create([
        'name' => 'Demo Customer',
        'email' => 'democustomer@example.com',
        'phone' => '08012345678',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    // Simulate merchant in merchant panel triggering "Send Password Reset Email Link"
    Livewire::test(ListCustomers::class)
        ->callTableAction('reset_security', $customer, [
            'action_type' => 'send_password_reset',
        ])
        ->assertHasNoTableActionErrors();

    Notification::assertSentTo($customer, TenantResetPasswordNotification::class, function ($notification) use ($customer) {
        $mail = $notification->toMail($customer);
        $actionUrl = $mail->actionUrl;

        // Ensure it points to the customer's storefront domain and NEVER the merchant portal
        expect($actionUrl)
            ->not->toContain('merchant.')
            ->toContain('apexhub.localhost')
            ->toContain('/reset-password/'.$notification->token)
            ->toContain('email='.urlencode($customer->email));

        return true;
    });
});

test('when store has a custom domain, password reset email points to custom domain', function () {
    Notification::fake();

    Domain::create([
        'tenant_id' => $this->store->id,
        'domain' => 'apextelecom.ng',
        'is_primary' => true,
        'is_verified' => true,
        'is_routing_enabled' => true,
        'status' => 'verified',
        'verification_status' => Domain::VERIFY_ACTIVE,
    ]);

    $customer = User::create([
        'name' => 'Custom Domain Customer',
        'email' => 'customcustomer@example.com',
        'phone' => '08087654321',
        'password' => bcrypt('password'),
        'store_id' => $this->store->id,
        'is_active' => true,
    ]);

    Livewire::test(ListCustomers::class)
        ->callTableAction('reset_security', $customer, [
            'action_type' => 'send_password_reset',
        ])
        ->assertHasNoTableActionErrors();

    Notification::assertSentTo($customer, TenantResetPasswordNotification::class, function ($notification) use ($customer) {
        $mail = $notification->toMail($customer);
        $actionUrl = $mail->actionUrl;

        expect($actionUrl)
            ->not->toContain('merchant.')
            ->toContain('apextelecom.ng')
            ->toContain('/reset-password/'.$notification->token)
            ->toContain('email='.urlencode($customer->email));

        return true;
    });
});
