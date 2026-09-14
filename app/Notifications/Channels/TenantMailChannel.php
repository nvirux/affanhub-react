<?php

namespace App\Notifications\Channels;

use App\Services\Mail\TenantMailService;
use Illuminate\Notifications\Notification;

class TenantMailChannel
{
    public function __construct(protected TenantMailService $mailService) {}

    /**
     * Send the given notification via the custom tenant mail channel.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toTenantMail')) {
            return;
        }

        $data = $notification->toTenantMail($notifiable);

        if (! empty($data) && ! empty($data['store'])) {
            $to = $notifiable->routeNotificationFor('mail', $notification) ?: $notifiable->email;

            $this->mailService->send(
                store: $data['store'],
                to: $to,
                subject: $data['subject'],
                htmlContent: $data['html']
            );
        }
    }
}
