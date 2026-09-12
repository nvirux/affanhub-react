<?php

use App\Filament\Merchant\Widgets\OnboardingChecklistWidget;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Checklist Merchant',
        'email' => 'checklist@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Al-Amin Vending Hub',
        'owner_id' => $this->owner->id,
    ]);
    $this->owner->stores()->attach($this->store->id, ['role' => 'owner']);

    $this->starterPlan = Plan::create([
        'name' => 'Starter',
        'slug' => 'starter',
        'description' => 'Starter plan',
        'price_monthly' => 0.00,
        'price_yearly' => 0.00,
        'trial_days' => 0,
        'is_active' => true,
    ]);

    $this->store->subscriptions()->create([
        'plan_id' => $this->starterPlan->id,
        'price' => 0.00,
        'billing_interval' => 'month',
        'status' => 'active',
        'starts_at' => now(),
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
});

test('onboarding checklist widget renders on merchant dashboard', function () {
    Livewire::test(OnboardingChecklistWidget::class)
        ->assertSuccessful()
        ->assertSee('Get Your Store Ready for Business')
        ->assertSee('Create your digital storefront')
        ->assertSee('Set your selling prices & profit margins')
        ->assertSee('Fund your vending wallet');
});

test('onboarding checklist computes step completion dynamically', function () {
    $widget = Livewire::test(OnboardingChecklistWidget::class);
    $data = $widget->instance()->getStepsData();

    // Initial state: Step 1 is done, others pending
    expect($data['completedCount'])->toBeGreaterThanOrEqual(1);
    expect($data['progress'])->toBeGreaterThan(0);

    // Now fund the store wallet
    $this->store->mainWallet()->update(['balance' => 15000.00]);

    $updatedData = $widget->instance()->getStepsData();
    $walletStep = collect($updatedData['steps'])->firstWhere('id', 'fund_wallet');
    expect($walletStep['completed'])->toBeTrue();
});

test('merchant can dismiss the onboarding checklist', function () {
    Livewire::test(OnboardingChecklistWidget::class)
        ->call('dismiss')
        ->assertNotified('Checklist Dismissed');

    $this->store->refresh();
    expect($this->store->dismiss_onboarding_checklist)->toBeTrue();

    // Refresh tenant on Filament manager
    Filament::setTenant($this->store);

    // canView should now return false
    expect(OnboardingChecklistWidget::canView())->toBeFalse();
});

test('merchant can copy storefront link and receives notification', function () {
    Livewire::test(OnboardingChecklistWidget::class)
        ->call('markLinkCopied')
        ->assertNotified('Link Copied');
});
