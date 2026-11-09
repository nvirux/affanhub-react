<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Withdrawal;
use App\Services\Notification\AdminNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminWithdrawalRequestedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Withdrawal $withdrawal
    ) {}

    public function envelope(): Envelope
    {
        $net = (float) ($this->withdrawal->net_amount ?? $this->withdrawal->amount);
        $storeName = $this->withdrawal->store?->name ?? 'Merchant';

        return new Envelope(
            from: new Address(config('mail.from.address', 'support@affanhub.com'), config('mail.from.name', 'AffanHub Alerts')),
            subject: sprintf('💰 [Payout Request] ₦%s from %s (Ref: %s)', number_format($net, 2), $storeName, $this->withdrawal->reference),
        );
    }

    public function content(): Content
    {
        $amount = (float) $this->withdrawal->amount;
        $fee = (float) ($this->withdrawal->fee ?? 0);
        $netAmount = (float) ($this->withdrawal->net_amount ?? max(0, $amount - $fee));

        return new Content(
            view: 'emails.admin-withdrawal-requested',
            with: [
                'withdrawal' => $this->withdrawal,
                'store' => $this->withdrawal->store,
                'owner' => $this->withdrawal->store?->owner,
                'amount' => $amount,
                'fee' => $fee,
                'netAmount' => $netAmount,
                'adminUrl' => AdminNotificationService::getAdminUrl('withdrawals'),
                'appName' => config('app.name', 'AffanHub'),
            ],
        );
    }
}
