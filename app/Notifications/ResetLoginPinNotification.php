<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetLoginPinNotification extends Notification
{
    use Queueable;

    /**
     * Create a notification instance.
     */
    public function __construct(public string $token, public string $resetUrl) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Your 4-Digit Login PIN')
            ->line('You are receiving this email because we received a request to reset the 4-digit Login PIN for your account.')
            ->action('Reset Login PIN', $this->resetUrl)
            ->line('This PIN reset link will expire in 60 minutes.')
            ->line('If you did not request a PIN reset, please ignore this email or contact support if you suspect unauthorized activity.');
    }
}
