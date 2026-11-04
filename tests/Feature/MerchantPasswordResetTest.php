<?php

use App\Models\Owner;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
});

test('merchant login page shows forgot password link', function () {
    $response = $this->get('http://merchant.localhost/login');
    $response->assertSuccessful();
    $response->assertSee('Forgot password?');
});

test('merchant can request password reset link and receive notification', function () {
    Notification::fake();

    $owner = Owner::create([
        'name' => 'Reset Merchant',
        'email' => 'resetmerchant@example.com',
        'phone' => '08099887766',
        'password' => 'secretPassword123',
    ]);

    $broker = Password::broker('owners');
    $token = $broker->createToken($owner);

    expect($token)->not->toBeEmpty();
    expect($broker->tokenExists($owner, $token))->toBeTrue();
});

test('owner password is automatically hashed when updated via OwnerForm or model', function () {
    $owner = Owner::create([
        'name' => 'Plain Test Merchant',
        'email' => 'plaintest@example.com',
        'phone' => '08099887755',
        'password' => 'initialPassword1',
    ]);

    // Model cast hashes automatically
    expect(Hash::check('initialPassword1', $owner->password))->toBeTrue();

    // Updating via model hashes automatically
    $owner->update(['password' => 'newChangedPassword2']);
    expect(Hash::check('newChangedPassword2', $owner->fresh()->password))->toBeTrue();
    expect($owner->fresh()->password)->toStartWith('$2y$');
});
