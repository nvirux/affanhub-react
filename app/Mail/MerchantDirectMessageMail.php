<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Owner;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MerchantDirectMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Owner $owner,
        public string $customSubject,
        public string $customBody
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address', 'support@affanhub.com'), config('mail.from.name', 'AffanHub Support')),
            subject: $this->customSubject,
        );
    }

    public function content(): Content
    {
        $scheme = request()->getScheme() ?: 'https';
        $appHost = parse_url(config('app.url', 'https://affanhub.com'), PHP_URL_HOST) ?: 'affanhub.com';
        $port = request()->getPort();
        $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';
        $cleanHost = preg_replace('/^(admin|merchant)\./', '', $appHost);

        $loginUrl = "{$scheme}://merchant.{$cleanHost}{$portStr}";

        return new Content(
            view: 'emails.merchant-direct-message',
            with: [
                'name' => $this->owner->name,
                'email' => $this->owner->email,
                'bodyContent' => $this->customBody,
                'loginUrl' => $loginUrl,
                'appName' => config('app.name', 'AffanHub'),
            ],
        );
    }
}
