<?php

declare(strict_types=1);

namespace App\Services\Subscription;

use App\Mail\SubscriptionExpiredDowngradedMail;
use App\Mail\SubscriptionGracePeriodMail;
use App\Mail\SubscriptionReminderMail;
use App\Models\ActivityLog;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SubscriptionLifecycleService
{
    /**
     * Run all steps of the subscription lifecycle.
     *
     * @return array<string, int>
     */
    public function processAll(): array
    {
        $remindersSent = $this->processExpiringReminders();
        $graceTransitions = $this->processGraceTransitions();
        $downgrades = $this->processExpiredDowngrades();

        return [
            'reminders_sent' => $remindersSent,
            'grace_transitions' => $graceTransitions,
            'downgrades' => $downgrades,
        ];
    }

    /**
     * Send 3-day and 1-day pre-expiration notices.
     */
    public function processExpiringReminders(): int
    {
        $now = now();
        $count = 0;

        $expiringSubscriptions = Subscription::with(['store.owner', 'plan'])
            ->where('status', 'active')
            ->where('price', '>', 0)
            ->whereNotNull('ends_at')
            ->where('ends_at', '>', $now)
            ->where('ends_at', '<=', $now->copy()->addDays(3))
            ->get();

        foreach ($expiringSubscriptions as $subscription) {
            $store = $subscription->store;
            $owner = $store?->owner;
            if (! $store || ! $owner) {
                continue;
            }

            $diffHours = $now->diffInHours($subscription->ends_at, false);
            $daysRemaining = (int) ceil(max(1, $diffHours) / 24);

            // 1-Day Urgent Reminder
            if ($diffHours <= 24 && $subscription->reminder_1day_sent_at === null) {
                $this->sendReminder($subscription, $daysRemaining, isUrgent: true);
                $subscription->update(['reminder_1day_sent_at' => $now]);
                $count++;
            }
            // 3-Day Friendly Reminder
            elseif ($diffHours <= 72 && $diffHours > 24 && $subscription->reminder_3days_sent_at === null) {
                $this->sendReminder($subscription, $daysRemaining, isUrgent: false);
                $subscription->update(['reminder_3days_sent_at' => $now]);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Transition subscriptions that reached ends_at into a 3-day grace period.
     */
    public function processGraceTransitions(): int
    {
        $now = now();
        $count = 0;

        $expiredActive = Subscription::with(['store.owner', 'plan'])
            ->where('status', 'active')
            ->where('price', '>', 0)
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', $now)
            ->get();

        foreach ($expiredActive as $subscription) {
            $store = $subscription->store;
            $owner = $store?->owner;
            if (! $store || ! $owner) {
                continue;
            }

            $graceEndsAt = $subscription->ends_at->copy()->addDays(3);

            $subscription->update([
                'status' => 'past_due',
                'grace_ends_at' => $graceEndsAt,
                'grace_notified_at' => $now,
            ]);

            // 1. Email Merchant
            try {
                Mail::to($owner->email)->queue(new SubscriptionGracePeriodMail($subscription, 3));
            } catch (Throwable $e) {
                report($e);
            }

            // 2. In-App Notification
            try {
                $planName = $subscription->plan?->name ?? 'Pro';
                $billingUrl = $this->getBillingUrl($store);

                Notification::make()
                    ->title('🚨 3-Day Grace Period Active')
                    ->body(sprintf(
                        'Your %s Plan for %s expired on %s. You have 3 days of grace until %s to renew before automatic downgrade to the Starter Plan.',
                        $planName,
                        $store->name,
                        $subscription->ends_at->format('M j, Y'),
                        $graceEndsAt->format('M j, Y')
                    ))
                    ->danger()
                    ->persistent()
                    ->actions([
                        Action::make('renew')
                            ->label('Renew Plan Now')
                            ->url($billingUrl),
                    ])
                    ->sendToDatabase($owner);
            } catch (Throwable $e) {
                report($e);
            }

            ActivityLog::create([
                'tenant_id' => $store->id,
                'event' => 'subscription_grace_period_started',
                'description' => sprintf('Store subscription reached ends_at (%s). Transitioned to 3-day grace period ending %s.', $subscription->ends_at->format('Y-m-d'), $graceEndsAt->format('Y-m-d')),
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Mark expired subscriptions past grace period and downgrade store to Starter plan.
     */
    public function processExpiredDowngrades(): int
    {
        $now = now();
        $count = 0;

        $pastGrace = Subscription::with(['store.owner', 'plan'])
            ->where('status', 'past_due')
            ->whereNotNull('grace_ends_at')
            ->where('grace_ends_at', '<=', $now)
            ->get();

        foreach ($pastGrace as $subscription) {
            $store = $subscription->store;
            $owner = $store?->owner;
            if (! $store || ! $owner) {
                continue;
            }

            // Mark expired
            $subscription->update([
                'status' => 'expired',
                'downgraded_at' => $now,
            ]);

            // Downgrade to Starter
            $starterPlan = Plan::firstOrCreate(
                ['slug' => 'starter'],
                [
                    'name' => 'Starter',
                    'price_monthly' => 0.00,
                    'price_yearly' => 0.00,
                    'is_active' => true,
                    'description' => 'Free Starter Plan with basic features',
                ]
            );

            $store->subscriptions()->create([
                'plan_id' => $starterPlan->id,
                'price' => 0.00,
                'billing_interval' => 'month',
                'status' => 'active',
                'starts_at' => $now,
                'ends_at' => null,
            ]);

            // 1. Email Merchant
            try {
                Mail::to($owner->email)->queue(new SubscriptionExpiredDowngradedMail($subscription, $starterPlan));
            } catch (Throwable $e) {
                report($e);
            }

            // 2. In-App Notification
            try {
                $billingUrl = $this->getBillingUrl($store);

                Notification::make()
                    ->title('ℹ️ Plan Downgraded to Starter')
                    ->body(sprintf('Grace period for %s has ended. Your store has been transitioned to the free Starter Plan. Upgrade anytime to restore Pro wholesale pricing and custom domain features.', $store->name))
                    ->warning()
                    ->persistent()
                    ->actions([
                        Action::make('upgrade')
                            ->label('Upgrade Plan')
                            ->url($billingUrl),
                    ])
                    ->sendToDatabase($owner);
            } catch (Throwable $e) {
                report($e);
            }

            ActivityLog::create([
                'tenant_id' => $store->id,
                'event' => 'subscription_downgraded_to_starter',
                'description' => sprintf('Grace period ended. Subscription #%d marked expired and store automatically downgraded to Starter Plan.', $subscription->id),
            ]);

            $count++;
        }

        return $count;
    }

    protected function sendReminder(Subscription $subscription, int $daysRemaining, bool $isUrgent): void
    {
        $store = $subscription->store;
        $owner = $store?->owner;
        if (! $store || ! $owner) {
            return;
        }

        $planName = $subscription->plan?->name ?? 'Pro';
        $billingUrl = $this->getBillingUrl($store);

        // 1. Send Email
        try {
            Mail::to($owner->email)->queue(new SubscriptionReminderMail($subscription, $daysRemaining));
        } catch (Throwable $e) {
            report($e);
        }

        // 2. In-App Database Notification
        try {
            $title = $isUrgent
                ? "⚠️ Urgent: {$planName} Plan Expires Tomorrow!"
                : "⏳ {$planName} Plan Expires in {$daysRemaining} Days";

            $body = sprintf(
                'Your %s Plan for %s will expire on %s. Renew now to avoid custom domain routing interruption or loss of wholesale discounts.',
                $planName,
                $store->name,
                $subscription->ends_at?->format('M j, Y') ?? 'soon'
            );

            Notification::make()
                ->title($title)
                ->body($body)
                ->warning()
                ->actions([
                    Action::make('renew')
                        ->label('Renew Plan')
                        ->url($billingUrl),
                ])
                ->sendToDatabase($owner);
        } catch (Throwable $e) {
            report($e);
        }
    }

    protected function getBillingUrl(Store $store): string
    {
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: 'https';
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $port = parse_url(config('app.url'), PHP_URL_PORT);
        $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';
        $cleanHost = preg_replace('/^(admin|merchant)\./', '', $host);

        return "{$scheme}://merchant.{$cleanHost}{$portStr}/billing";
    }
}
