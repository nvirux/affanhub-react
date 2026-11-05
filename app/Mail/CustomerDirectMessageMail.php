<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerDirectMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $customSubject,
        public string $customBody
    ) {}

    public function envelope(): Envelope
    {
        $storeName = $this->user->store?->name ?? config('app.name', 'AffanHub');

        return new Envelope(
            from: new Address(config('mail.from.address', 'support@affanhub.com'), "{$storeName} Support"),
            subject: $this->customSubject,
        );
    }

    public function content(): Content
    {
        $storeName = $this->user->store?->name ?? config('app.name', 'AffanHub');
        $portalUrl = $this->user->store ? 'https://'.$this->user->store->cname : config('app.url', 'https://affanhub.com');

        return new Content(
            view: 'emails.customer-direct-message',
            with: [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'bodyContent' => $this->customBody,
                'storeName' => $storeName,
                'portalUrl' => $portalUrl,
            ],
        );
    }
}
