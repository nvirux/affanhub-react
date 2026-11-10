<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionGracePeriodMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public int $graceDays = 3
    ) {}

    public function envelope(): Envelope
    {
        $planName = $this->subscription->plan?->name ?? 'Pro';
        $storeName = $this->subscription->store?->name ?? 'Your Store';

        return new Envelope(
            from: new Address(config('mail.from.address', 'support@affanhub.com'), config('mail.from.name', 'AffanHub Billing')),
            subject: "🚨 Grace Period Active: Your {$planName} Plan for {$storeName} expired today",
        );
    }

    public function content(): Content
    {
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: 'https';
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $port = parse_url(config('app.url'), PHP_URL_PORT);
        $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';
        $cleanHost = preg_replace('/^(admin|merchant)\./', '', $host);

        $store = $this->subscription->store;
        $billingUrl = "{$scheme}://merchant.{$cleanHost}{$portStr}/billing";

        return new Content(
            view: 'emails.merchant-subscription-grace-period',
            with: [
                'subscription' => $this->subscription,
                'plan' => $this->subscription->plan,
                'store' => $store,
                'owner' => $store?->owner,
                'graceDays' => $this->graceDays,
                'graceEndsAt' => $this->subscription->grace_ends_at,
                'billingUrl' => $billingUrl,
                'appName' => config('app.name', 'AffanHub'),
            ],
        );
    }
}
