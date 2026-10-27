<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

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
            ->greeting("Hello {$notifiable->name},")
            ->line("Welcome to {$appName}! Please use the 6-digit code below to verify your merchant email address:")
            ->line(new HtmlString("
                <div style=\"text-align: center; margin: 32px 0;\">
                    <span style=\"display: inline-block; background-color: #fef3c7; color: #92400e; font-size: 32px; font-weight: 700; letter-spacing: 8px; padding: 14px 28px; border-radius: 8px; border: 1px solid #fde68a; font-family: monospace;\">
                        {$this->otp}
                    </span>
                </div>
            "))
            ->line('This code will expire in 15 minutes. Enter this code on your screen to complete your merchant account setup.')
            ->line('If you did not register for an account on AffanHub, please ignore this email.');
    }
}
