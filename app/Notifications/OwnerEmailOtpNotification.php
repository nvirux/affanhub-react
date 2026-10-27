<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerEmailOtpNotification extends Notification
{
    use Queueable;

    public function __construct(public string $otp) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $appName = config('app.name', 'AffanHub');

        return (new MailMessage)
            ->subject("{$this->otp} is your {$appName} verification code")
            ->view('emails.merchant-otp', [
                'name' => $notifiable->name ?? 'Merchant',
                'otp' => $this->otp,
                'appName' => $appName,
            ]);
    }
}
