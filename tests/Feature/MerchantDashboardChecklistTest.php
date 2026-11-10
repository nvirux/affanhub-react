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
        ->assertSee('Get Your Website Ready for Business')
        ->assertSee('Set your selling prices & profit margins')
        ->assertSee('Personalize your website & WhatsApp support')
        ->assertSee('Add your bank settlement account')
        ->assertSee('Join our Merchant WhatsApp Community')
        ->assertSee('Get your first customer');
});

test('onboarding checklist computes step completion dynamically', function () {
    $widget = Livewire::test(OnboardingChecklistWidget::class);
    $data = $widget->instance()->getStepsData();

    // Initial state: 5 steps
    expect($data['totalCount'])->toBe(5);

    // Now customize store branding
    $this->store->update(['whatsapp_chat_phone' => '07011507950']);

    $updatedData = $widget->instance()->getStepsData();
    $brandingStep = collect($updatedData['steps'])->firstWhere('id', 'customize_branding');
    expect($brandingStep['completed'])->toBeTrue();
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

test('merchant can mark community joined and step completes', function () {
    $widget = Livewire::test(OnboardingChecklistWidget::class)
        ->call('markCommunityJoined');

    $this->store->refresh();
    expect($this->store->joined_whatsapp_community)->toBeTrue();

    $data = $widget->instance()->getStepsData();
    $communityStep = collect($data['steps'])->firstWhere('id', 'join_community');
    expect($communityStep['completed'])->toBeTrue();
});

test('merchant can copy storefront link and receives notification', function () {
    Livewire::test(OnboardingChecklistWidget::class)
        ->call('markLinkCopied')
        ->assertNotified('Link Copied');
});
