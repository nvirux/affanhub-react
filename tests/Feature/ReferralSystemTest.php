<?php

use App\Models\Feature;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Referral;
use App\Models\Store;
use App\Models\StoreReferralSetting;
use App\Models\User;
use App\Services\ReferralService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    // 1. Create Owner & Store
    $this->owner = Owner::create([
        'name' => 'Merchant Owner',
        'email' => 'merchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Test Telecom',
        'owner_id' => $this->owner->id,
    ]);

    DB::table('domains')->insert([
        'tenant_id' => $this->store->id,
        'domain' => 'telecom.localhost',
        'is_primary' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 2. Setup Pro Plan with referral_system enabled
    $this->referralFeature = Feature::create([
        'name' => 'Referral & Earn System',
        'slug' => 'referral_system',
        'type' => 'boolean',
        'default_value' => 'false',
    ]);

    $this->proPlan = Plan::create([
        'name' => 'Pro Plan',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'trial_days' => 14,
        'is_active' => true,
    ]);

    $this->proPlan->planFeatures()->create([
        'feature_id' => $this->referralFeature->id,
        'value' => 'true',
    ]);

    $this->store->subscriptions()->create([
        'plan_id' => $this->proPlan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'status' => 'active',
        'starts_at' => now(),
        'ends_at' => now()->addMonth(),
    ]);

    // 3. Store Referral Settings (₦50 reward, min deposit ₦500)
    $this->referralSetting = StoreReferralSetting::create([
        'store_id' => $this->store->id,
        'is_enabled' => true,
        'reward_amount' => 50.00,
        'condition_type' => 'first_deposit',
        'min_deposit_amount' => 500.00,
    ]);

    // 4. Create Referrer User
    $this->referrer = User::factory()->create([
        'store_id' => $this->store->id,
        'referral_code' => 'TESTREF1',
    ]);

    $this->walletService = app(WalletService::class);
    $this->referralService = app(ReferralService::class);
});

test('recording registration creates pending referral when store has feature', function () {
    $newUser = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    $referral = $this->referralService->recordRegistration($newUser, 'TESTREF1');

    expect($referral)->not->toBeNull();
    expect($referral->referrer_id)->toBe($this->referrer->id);
    expect($referral->referred_id)->toBe($newUser->id);
    expect($referral->status)->toBe('pending');
    expect($newUser->fresh()->referred_by)->toBe($this->referrer->id);
});

test('registration referral fails if store lacks referral_system feature', function () {
    // Cancel subscription so store falls back to default_value (false)
    $this->store->subscriptions()->update(['status' => 'cancelled']);

    $newUser = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    $referral = $this->referralService->recordRegistration($newUser, 'TESTREF1');

    expect($referral)->toBeNull();
    expect(Referral::count())->toBe(0);
});

test('registration referral ignores self-referrals and duplicate recordings', function () {
    $referral = $this->referralService->recordRegistration($this->referrer, 'TESTREF1');
    expect($referral)->toBeNull();

    $newUser = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    $first = $this->referralService->recordRegistration($newUser, 'TESTREF1');
    expect($first)->not->toBeNull();

    // Second call should return null (no duplicate)
    $second = $this->referralService->recordRegistration($newUser, 'TESTREF1');
    expect($second)->toBeNull();
    expect(Referral::where('referred_id', $newUser->id)->count())->toBe(1);
});

test('deposit below minimum deposit does not reward referrer', function () {
    $newUser = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    $this->referralService->recordRegistration($newUser, 'TESTREF1');

    // Deposit ₦200 (threshold is ₦500)
    $userWallet = $newUser->wallet('main');
    $this->walletService->credit($userWallet, 200.00, 'deposit', 'Wallet funding');

    $referral = Referral::where('referred_id', $newUser->id)->first();
    expect($referral->status)->toBe('pending');

    $referrerWallet = $this->referrer->wallet('main');
    expect((float) $referrerWallet->balance)->toBe(0.0);
});

test('deposit meeting minimum deposit rewards referrer and completes referral', function () {
    $newUser = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    $this->referralService->recordRegistration($newUser, 'TESTREF1');

    // Deposit ₦500 (threshold is ₦500)
    $userWallet = $newUser->wallet('main');
    $this->walletService->credit($userWallet, 500.00, 'deposit', 'Wallet funding');

    $referral = Referral::where('referred_id', $newUser->id)->first();
    expect($referral->status)->toBe('completed');
    expect((float) $referral->reward_amount)->toBe(50.00);
    expect($referral->completed_at)->not->toBeNull();

    $referrerWallet = $this->referrer->wallet('main');
    expect((float) $referrerWallet->fresh()->balance)->toBe(50.00);
});

test('subsequent deposits do not reward referrer multiple times', function () {
    $newUser = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    $this->referralService->recordRegistration($newUser, 'TESTREF1');

    $userWallet = $newUser->wallet('main');
    $this->walletService->credit($userWallet, 1000.00, 'deposit', 'First funding');

    $referrerWallet = $this->referrer->wallet('main');
    expect((float) $referrerWallet->fresh()->balance)->toBe(50.00);

    // Second deposit of ₦2000
    $this->walletService->credit($userWallet, 2000.00, 'deposit', 'Second funding');
    expect((float) $referrerWallet->fresh()->balance)->toBe(50.00);
});

test('purchase condition rewards on first purchase but not on deposit', function () {
    $this->referralSetting->update([
        'condition_type' => 'first_purchase',
    ]);

    $newUser = User::factory()->create([
        'store_id' => $this->store->id,
    ]);

    $this->referralService->recordRegistration($newUser, 'TESTREF1');

    // Deposit does NOT complete referral when condition is first_purchase
    $userWallet = $newUser->wallet('main');
    $this->walletService->credit($userWallet, 1000.00, 'deposit', 'Wallet funding');

    $referral = Referral::where('referred_id', $newUser->id)->first();
    expect($referral->status)->toBe('pending');
    expect((float) $this->referrer->wallet('main')->fresh()->balance)->toBe(0.0);

    // Now trigger purchase qualification
    $rewarded = $this->referralService->checkAndReward($newUser, 'purchase', 200.00);
    expect($rewarded)->toBeTrue();

    expect($referral->fresh()->status)->toBe('completed');
    expect((float) $this->referrer->wallet('main')->fresh()->balance)->toBe(50.00);
});

test('customer can visit earn page and see referral stats and link', function () {
    $this->actingAs($this->referrer);

    $response = $this->get('http://telecom.localhost/earn');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Storefront/Earn/Index')
        ->has('referral_code')
        ->has('referral_link')
        ->where('referral_code', 'TESTREF1')
        ->has('stats')
        ->where('reward_amount', 50)
    );
});
