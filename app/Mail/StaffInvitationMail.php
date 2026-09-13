<?php

namespace App\Mail;

use App\Models\StaffInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public StaffInvitation $invitation
    ) {}

    public function envelope(): Envelope
    {
        $storeName = $this->invitation->store->name ?? 'AffanHub Merchant';

        return new Envelope(
            subject: "Invitation to join {$storeName} on AffanHub",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.staff-invitation',
            with: [
                'invitation' => $this->invitation,
                'store' => $this->invitation->store,
                'role' => ucfirst($this->invitation->role),
                'inviterName' => $this->invitation->invitedBy?->name ?? 'The Store Owner',
                'acceptUrl' => $this->invitation->getAcceptUrl(),
                'expiresAt' => $this->invitation->expires_at->format('M d, Y \a\t h:i A'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
