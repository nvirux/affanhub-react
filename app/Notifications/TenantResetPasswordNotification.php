<?php

namespace App\Notifications;

use App\Models\Store;
use App\Notifications\Channels\TenantMailChannel;
use App\Services\Mail\TenantMailService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class TenantResetPasswordNotification extends ResetPassword
{
    use Queueable;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Determine delivery channels based on whether the store has custom Resend configured.
     */
    public function via($notifiable)
    {
        $store = $this->getStore($notifiable);

        if ($store && $store->hasCustomEmail()) {
            return [TenantMailChannel::class];
        }

        return ['mail'];
    }

    /**
     * Payload for TenantMailChannel (Resend API).
     */
    public function toTenantMail($notifiable): array
    {
        $store = $this->getStore($notifiable);
        $storeName = $store?->name ?: config('app.name', 'AffanHub');
        $resetUrl = $this->resetUrl($notifiable);

        $mailService = app(TenantMailService::class);
        $html = $mailService->renderBrandedTemplate(
            store: $store,
            title: "Reset Password - {$storeName}",
            greeting: "Hello {$notifiable->name},",
            lines: [
                "You are receiving this email because we received a password reset request for your account on {$storeName}.",
                'Click the button below to choose a new secure password.',
            ],
            actionText: 'Reset Password',
            actionUrl: $resetUrl,
            subtext: 'This password reset link will expire in 60 minutes. If you did not request a password reset, no further action is required.'
        );

        return [
            'store' => $store,
            'subject' => "Reset Password - {$storeName}",
            'html' => $html,
        ];
    }

    /**
     * Build the standard MailMessage (with store branding) for fallback delivery.
     */
    public function toMail($notifiable): MailMessage
    {
        $store = $this->getStore($notifiable);
        $storeName = $store?->name ?: config('app.name', 'AffanHub');
        $resetUrl = $this->resetUrl($notifiable);

        $mailMessage = (new MailMessage)
            ->subject("Reset Password - {$storeName}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You are receiving this email because we received a password reset request for your account on {$storeName}.")
            ->action('Reset Password', $resetUrl)
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.')
            ->salutation("Regards,\n{$storeName}");

        if ($store) {
            $fromAddress = config('mail.from.address');
            $replyTo = $store->contact_email ?: $fromAddress;
            $mailMessage->from($fromAddress, $storeName)
                ->replyTo($replyTo, $storeName);
        }

        return $mailMessage;
    }

    protected function resetUrl($notifiable)
    {
        return route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
    }

    protected function getStore(object $notifiable): ?Store
    {
        if (isset($notifiable->store) && $notifiable->store instanceof Store) {
            return $notifiable->store;
        }

        if (! empty($notifiable->store_id)) {
            return Store::find($notifiable->store_id);
        }

        if (function_exists('tenant') && tenant() instanceof Store) {
            return tenant();
        }

        return null;
    }
}
