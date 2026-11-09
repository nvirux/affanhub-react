<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\SettlementAccount;
use App\Services\Notification\AdminNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminSettlementAccountSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SettlementAccount $account
    ) {}

    public function envelope(): Envelope
    {
        $storeName = $this->account->store?->name ?? 'Merchant';

        return new Envelope(
            from: new Address(config('mail.from.address', 'support@affanhub.com'), config('mail.from.name', 'AffanHub Alerts')),
            subject: sprintf('🏦 [Bank Approval Needed] %s - %s (%s)', $storeName, $this->account->bank_name, $this->account->account_number),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-settlement-submitted',
            with: [
                'account' => $this->account,
                'store' => $this->account->store,
                'owner' => $this->account->store?->owner,
                'adminUrl' => AdminNotificationService::getAdminUrl('settlement-accounts'),
                'appName' => config('app.name', 'AffanHub'),
            ],
        );
    }
}
