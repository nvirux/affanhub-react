<?php

use App\Mail\SubscriptionExpiredDowngradedMail;
use App\Mail\SubscriptionGracePeriodMail;
use App\Mail\SubscriptionReminderMail;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionLifecycleService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'Apex Merchant',
        'email' => 'apex@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'Apex Telecom Store',
        'public_id' => 'str_apex777',
        'owner_id' => $this->owner->id,
        'status' => 'active',
    ]);

    $this->proPlan = Plan::create([
        'name' => 'Pro',
        'slug' => 'pro',
        'price_monthly' => 5000.00,
        'price_yearly' => 50000.00,
        'is_active' => true,
    ]);

    $this->starterPlan = Plan::create([
        'name' => 'Starter',
        'slug' => 'starter',
        'price_monthly' => 0.00,
        'price_yearly' => 0.00,
        'is_active' => true,
    ]);
});

test('sends 3-day reminder email and database notification to merchant', function () {
    Mail::fake();

    $subscription = Subscription::create([
        'store_id' => $this->store->id,
        'plan_id' => $this->proPlan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'status' => 'active',
        'starts_at' => now()->subMonth(),
        'ends_at' => now()->addDays(2)->addHours(12), // ~2.5 days left
    ]);

    $service = app(SubscriptionLifecycleService::class);
    $results = $service->processAll();

    expect($results['reminders_sent'])->toBe(1);

    // Verify Mail queued
    Mail::assertQueued(SubscriptionReminderMail::class, function ($mail) {
        return $mail->hasTo('apex@example.com')
            && $mail->daysRemaining === 3;
    });

    // Verify in-app database notification
    $notification = $this->owner->notifications()->first();
    expect($notification)->not->toBeNull()
        ->and($notification->data['title'])->toContain('3 Days')
        ->and($notification->data['body'])->toContain('Apex Telecom Store');

    $subscription->refresh();
    expect($subscription->reminder_3days_sent_at)->not->toBeNull();
});

test('sends 1-day urgent reminder email and database notification to merchant', function () {
    Mail::fake();

    $subscription = Subscription::create([
        'store_id' => $this->store->id,
        'plan_id' => $this->proPlan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'status' => 'active',
        'starts_at' => now()->subMonth(),
        'ends_at' => now()->addHours(18), // Under 24h
        'reminder_3days_sent_at' => now()->subDays(2),
    ]);

    $service = app(SubscriptionLifecycleService::class);
    $results = $service->processAll();

    expect($results['reminders_sent'])->toBe(1);

    Mail::assertQueued(SubscriptionReminderMail::class, function ($mail) {
        return $mail->hasTo('apex@example.com')
            && $mail->daysRemaining <= 1;
    });

    $subscription->refresh();
    expect($subscription->reminder_1day_sent_at)->not->toBeNull();
});

test('transitions expired subscription to 3-day grace period with email and in-app notice', function () {
    Mail::fake();

    $subscription = Subscription::create([
        'store_id' => $this->store->id,
        'plan_id' => $this->proPlan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'status' => 'active',
        'starts_at' => now()->subMonth(),
        'ends_at' => now()->subHours(2), // Expired 2 hours ago
    ]);

    $service = app(SubscriptionLifecycleService::class);
    $results = $service->processAll();

    expect($results['grace_transitions'])->toBe(1);

    $subscription->refresh();
    expect($subscription->status)->toBe('past_due')
        ->and($subscription->grace_ends_at)->not->toBeNull()
        ->and($subscription->isInGracePeriod())->toBeTrue()
        ->and($subscription->isActive())->toBeTrue(); // Store remains functional in grace period!

    Mail::assertQueued(SubscriptionGracePeriodMail::class, function ($mail) {
        return $mail->hasTo('apex@example.com')
            && $mail->graceDays === 3;
    });

    $notification = $this->owner->notifications()->first();
    expect($notification)->not->toBeNull()
        ->and($notification->data['title'])->toContain('3-Day Grace Period Active');
});

test('downgrades store to Starter plan when grace period has ended', function () {
    Mail::fake();

    $expiredSub = Subscription::create([
        'store_id' => $this->store->id,
        'plan_id' => $this->proPlan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'status' => 'past_due',
        'starts_at' => now()->subMonth()->subDays(4),
        'ends_at' => now()->subDays(4),
        'grace_ends_at' => now()->subDay(), // Grace ended yesterday
    ]);

    $service = app(SubscriptionLifecycleService::class);
    $results = $service->processAll();

    expect($results['downgrades'])->toBe(1);

    $expiredSub->refresh();
    expect($expiredSub->status)->toBe('expired')
        ->and($expiredSub->downgraded_at)->not->toBeNull();

    // Check that store has new active Starter subscription
    $activeSub = $this->store->fresh()->activeSubscription;
    expect($activeSub)->not->toBeNull()
        ->and($activeSub->plan_id)->toBe($this->starterPlan->id)
        ->and($activeSub->status)->toBe('active')
        ->and((float) $activeSub->price)->toBe(0.00);

    Mail::assertQueued(SubscriptionExpiredDowngradedMail::class, function ($mail) {
        return $mail->hasTo('apex@example.com')
            && $mail->downgradedPlan->slug === 'starter';
    });

    $notification = $this->owner->notifications()->first();
    expect($notification)->not->toBeNull()
        ->and($notification->data['title'])->toContain('Plan Downgraded to Starter');
});

test('subscription warning banner renders when subscription is expiring soon or in grace period', function () {
    $subscription = Subscription::create([
        'store_id' => $this->store->id,
        'plan_id' => $this->proPlan->id,
        'price' => 5000.00,
        'billing_interval' => 'month',
        'status' => 'active',
        'starts_at' => now()->subMonth(),
        'ends_at' => now()->addDays(2),
    ]);

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);

    // 1. Expiring soon banner
    $html = view('filament.merchant.components.subscription-banner')->render();
    expect($html)
        ->toContain('Pro Plan Expires in')
        ->toContain('Renew Subscription');

    // 2. Grace period banner
    $subscription->update([
        'status' => 'past_due',
        'ends_at' => now()->subHours(5),
        'grace_ends_at' => now()->addDays(2),
    ]);
    $this->store->refresh();
    $this->store->unsetRelation('activeSubscription');

    $htmlGrace = view('filament.merchant.components.subscription-banner')->render();
    expect($htmlGrace)
        ->toContain('Grace Period Active: Pro Plan Expired')
        ->toContain('Renew Plan Now');
});
