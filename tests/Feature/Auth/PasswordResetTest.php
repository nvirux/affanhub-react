<?php

use App\Models\Owner;
use App\Models\Store;
use App\Models\User;
use App\Notifications\TenantResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::resetPasswords());

    $this->owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Demo Store',
        'public_id' => 'str_demo',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);

    $this->store->domains()->create([
        'domain' => 'demo.localhost',
    ]);
});

test('reset password link screen can be rendered', function () {
    $response = $this->get('http://demo.localhost/forgot-password');

    $response->assertOk();
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create(['store_id' => $this->store->id]);

    $this->post('http://demo.localhost/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, TenantResetPasswordNotification::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create(['store_id' => $this->store->id]);

    $this->post('http://demo.localhost/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, TenantResetPasswordNotification::class, function ($notification) {
        $response = $this->get('http://demo.localhost/reset-password/'.$notification->token);

        $response->assertOk();

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create(['store_id' => $this->store->id]);

    $this->post('http://demo.localhost/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, TenantResetPasswordNotification::class, function ($notification) use ($user) {
        $response = $this->post('http://demo.localhost/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('http://demo.localhost/login');

        return true;
    });
});

test('password cannot be reset with invalid token', function () {
    $user = User::factory()->create(['store_id' => $this->store->id]);

    $response = $this->post('http://demo.localhost/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('email');
});
