<?php

use App\Filament\Merchant\Pages\Auth\EmailVerificationPrompt;
use App\Models\Owner;
use App\Notifications\OwnerEmailOtpNotification;
use Filament\Facades\Filament;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('merchant'));
});

test('owner sendEmailVerificationNotification sends 6-digit OTP and caches it', function () {
    Notification::fake();

    $owner = Owner::create([
        'name' => 'OTP Owner',
        'email' => 'otpowner@example.com',
        'phone' => '08011112222',
        'password' => bcrypt('password'),
    ]);

    $owner->sendEmailVerificationNotification();

    Notification::assertSentTo($owner, OwnerEmailOtpNotification::class, function ($notification) use ($owner) {
        $otp = $notification->otp;
        expect(strlen($otp))->toBe(6)
            ->and(is_numeric($otp))->toBeTrue();

        $cachedHash = Cache::get("owner_email_otp_{$owner->id}");
        expect($cachedHash)->toBe(hash('sha256', $otp));

        return true;
    });
});

test('unverified owner can view OTP email verification prompt', function () {
    $owner = Owner::create([
        'name' => 'Prompt Owner',
        'email' => 'prompt@example.com',
        'phone' => '08011112223',
        'password' => bcrypt('password'),
    ]);

    Filament::auth()->login($owner);

    Livewire::test(EmailVerificationPrompt::class)
        ->assertSuccessful()
        ->assertSee('Verify your Email Address')
        ->assertSee('prompt@example.com');
});

test('submitting valid 6-digit OTP marks owner email as verified and redirects', function () {
    Event::fake([Verified::class]);

    $owner = Owner::create([
        'name' => 'Valid OTP Owner',
        'email' => 'validotp@example.com',
        'phone' => '08011112224',
        'password' => bcrypt('password'),
    ]);

    $validOtp = '654321';
    Cache::put("owner_email_otp_{$owner->id}", hash('sha256', $validOtp), now()->addMinutes(15));

    Filament::auth()->login($owner);

    Livewire::test(EmailVerificationPrompt::class)
        ->set('code', $validOtp)
        ->call('verifyCode')
        ->assertHasNoErrors()
        ->assertRedirect(Filament::getUrl());

    expect($owner->fresh()->hasVerifiedEmail())->toBeTrue();
    expect(Cache::has("owner_email_otp_{$owner->id}"))->toBeFalse();
    expect(session('pixel_event'))->toBe([
        'event' => 'CompleteRegistration',
        'params' => [
            'content_name' => 'Merchant Account Verified',
            'status' => true,
        ],
    ]);
    Event::assertDispatched(Verified::class);
});

test('submitting invalid OTP code shows validation error and does not verify email', function () {
    $owner = Owner::create([
        'name' => 'Invalid OTP Owner',
        'email' => 'invalidotp@example.com',
        'phone' => '08011112225',
        'password' => bcrypt('password'),
    ]);

    $realOtp = '123456';
    Cache::put("owner_email_otp_{$owner->id}", hash('sha256', $realOtp), now()->addMinutes(15));

    Filament::auth()->login($owner);

    Livewire::test(EmailVerificationPrompt::class)
        ->set('code', '999999')
        ->call('verifyCode')
        ->assertHasErrors(['code']);

    expect($owner->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('too many invalid attempts locks the OTP code', function () {
    $owner = Owner::create([
        'name' => 'Lockout Owner',
        'email' => 'lockout@example.com',
        'phone' => '08011112226',
        'password' => bcrypt('password'),
    ]);

    $realOtp = '123456';
    Cache::put("owner_email_otp_{$owner->id}", hash('sha256', $realOtp), now()->addMinutes(15));
    // Simulate 5 prior failed attempts
    Cache::put("owner_email_otp_attempts_{$owner->id}", 5, now()->addMinutes(15));

    Filament::auth()->login($owner);

    Livewire::test(EmailVerificationPrompt::class)
        ->set('code', $realOtp)
        ->call('verifyCode')
        ->assertHasErrors(['code']);

    expect($owner->fresh()->hasVerifiedEmail())->toBeFalse();
    expect(Cache::has("owner_email_otp_{$owner->id}"))->toBeFalse();
});

test('resendCode action sends fresh 6-digit OTP code', function () {
    Notification::fake();

    $owner = Owner::create([
        'name' => 'Resend Owner',
        'email' => 'resend@example.com',
        'phone' => '08011112227',
        'password' => bcrypt('password'),
    ]);

    Filament::auth()->login($owner);

    Livewire::test(EmailVerificationPrompt::class)
        ->call('resendCode')
        ->assertHasNoErrors();

    Notification::assertSentTo($owner, OwnerEmailOtpNotification::class);
});

test('already verified owner is redirected away from verification prompt', function () {
    $owner = Owner::create([
        'name' => 'Verified Owner',
        'email' => 'verified@example.com',
        'phone' => '08011112228',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]);

    Filament::auth()->login($owner);

    Livewire::test(EmailVerificationPrompt::class)
        ->assertRedirect(Filament::getUrl());
});
