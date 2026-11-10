<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiredDowngradedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $expiredSubscription,
        public Plan $downgradedPlan
    ) {}

    public function envelope(): Envelope
    {
        $oldPlanName = $this->expiredSubscription->plan?->name ?? 'Pro';
        $storeName = $this->expiredSubscription->store?->name ?? 'Your Store';

        return new Envelope(
            from: new Address(config('mail.from.address', 'support@affanhub.com'), config('mail.from.name', 'AffanHub Billing')),
            subject: "ℹ️ Plan Downgrade Notice: {$storeName} transitioned to {$this->downgradedPlan->name} Plan",
        );
    }

    public function content(): Content
    {
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: 'https';
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $port = parse_url(config('app.url'), PHP_URL_PORT);
        $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';
        $cleanHost = preg_replace('/^(admin|merchant)\./', '', $host);

        $store = $this->expiredSubscription->store;
        $billingUrl = "{$scheme}://merchant.{$cleanHost}{$portStr}/billing";

        return new Content(
            view: 'emails.merchant-subscription-expired',
            with: [
                'subscription' => $this->expiredSubscription,
                'oldPlan' => $this->expiredSubscription->plan,
                'newPlan' => $this->downgradedPlan,
                'store' => $store,
                'owner' => $store?->owner,
                'billingUrl' => $billingUrl,
                'appName' => config('app.name', 'AffanHub'),
            ],
        );
    }
}
