<?php

namespace App\Services\Mail;

use App\Models\Store;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TenantMailService
{
    /**
     * Send a branded email for a tenant.
     * Uses Resend if configured and entitled, otherwise falls back to system mailer with tenant branding.
     */
    public function send(
        Store $store,
        string $to,
        string $subject,
        string $htmlContent,
        ?string $text = null
    ): bool {
        if ($store->hasCustomEmail()) {
            $fromName = $store->resend_from_name ?: $store->name;
            $fromAddress = "{$fromName} <{$store->resend_from_email}>";

            try {
                $response = Http::timeout(10)
                    ->withToken($store->resend_api_key)
                    ->post('https://api.resend.com/emails', [
                        'from' => $fromAddress,
                        'to' => [$to],
                        'subject' => $subject,
                        'html' => $htmlContent,
                        'text' => $text,
                        'reply_to' => $store->contact_email ?: $store->resend_from_email,
                    ]);

                if ($response->successful()) {
                    Log::info("Tenant email sent via Resend for store #{$store->id} to {$to}");

                    return true;
                }

                Log::warning("Tenant Resend API failed for store #{$store->id}: {$response->body()}. Falling back to system mailer.");
            } catch (\Throwable $e) {
                Log::error("Tenant Resend exception for store #{$store->id}: {$e->getMessage()}. Falling back to system mailer.");
            }
        }

        // Fallback to system mailer with tenant branding
        try {
            $fromName = $store->name ?: config('mail.from.name', 'AffanHub');
            $fromAddress = config('mail.from.address');
            $replyTo = $store->contact_email ?: $fromAddress;

            Mail::html($htmlContent, function ($message) use ($to, $subject, $fromName, $fromAddress, $replyTo) {
                $message->to($to)
                    ->from($fromAddress, $fromName)
                    ->replyTo($replyTo, $fromName)
                    ->subject($subject);
            });

            Log::info("Tenant email sent via System Mailer for store #{$store->id} to {$to}");

            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to send tenant email via System Mailer for store #{$store->id}: {$e->getMessage()}");

            return false;
        }
    }

    /**
     * Send a live test email to verify Resend credentials before saving.
     */
    public function sendTestEmail(
        Store $store,
        string $apiKey,
        string $fromEmail,
        ?string $fromName,
        string $recipient
    ): array {
        $senderName = $fromName ?: $store->name;
        $fromAddress = "{$senderName} <{$fromEmail}>";

        $html = $this->renderBrandedTemplate(
            store: $store,
            title: 'Resend Connection Verified',
            greeting: "Hello {$senderName} Team,",
            lines: [
                'Congratulations! Your custom Resend integration is successfully connected and verified.',
                "All outgoing transactional emails (such as password resets, login alerts, and receipts) for {$store->name} can now be delivered directly through your custom domain.",
            ],
            actionText: 'Go to Store Dashboard',
            actionUrl: $store->getStoreUrl()
        );

        try {
            $response = Http::timeout(10)
                ->withToken($apiKey)
                ->post('https://api.resend.com/emails', [
                    'from' => $fromAddress,
                    'to' => [$recipient],
                    'subject' => "[Test] Resend Email Connected - {$store->name}",
                    'html' => $html,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => "Test email successfully delivered to {$recipient} via Resend!",
                ];
            }

            $errorData = $response->json();
            $errorMessage = $errorData['message'] ?? $response->body() ?? 'Failed to send test email through Resend.';

            return [
                'success' => false,
                'error' => $errorMessage,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Render a clean, responsive HTML email template branded with the store's identity.
     */
    public function renderBrandedTemplate(
        Store $store,
        string $title,
        string $greeting,
        array $lines,
        ?string $actionText = null,
        ?string $actionUrl = null,
        ?string $subtext = null
    ): string {
        $primaryColor = $store->primary_color ?: '#2563EB';
        $storeName = htmlspecialchars($store->name, ENT_QUOTES, 'UTF-8');
        $logoUrl = $store->logo_path ? Storage::url($store->logo_path) : null;
        $currentYear = date('Y');

        $linesHtml = '';
        foreach ($lines as $line) {
            $linesHtml .= '<p style="margin: 0 0 16px; font-size: 15px; line-height: 24px; color: #374151;">'.htmlspecialchars($line, ENT_QUOTES, 'UTF-8').'</p>';
        }

        $actionHtml = '';
        if ($actionText && $actionUrl) {
            $safeUrl = htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8');
            $safeText = htmlspecialchars($actionText, ENT_QUOTES, 'UTF-8');
            $actionHtml = <<<HTML
            <div style="margin: 32px 0; text-align: center;">
                <a href="{$safeUrl}" style="background-color: {$primaryColor}; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; display: inline-block; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    {$safeText}
                </a>
            </div>
HTML;
        }

        $logoHtml = $logoUrl
            ? '<img src="'.htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8').'" alt="'.$storeName.'" style="max-height: 48px; max-width: 180px; margin-bottom: 12px;" />'
            : '<h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #111827;">'.$storeName.'</h1>';

        $subtextHtml = $subtext
            ? '<p style="margin: 24px 0 0; font-size: 13px; line-height: 20px; color: #6B7280; border-top: 1px solid #E5E7EB; padding-top: 16px;">'.htmlspecialchars($subtext, ENT_QUOTES, 'UTF-8').'</p>'
            : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F9FAFB; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #F3F4F6;">
                    <!-- Brand Header -->
                    <tr>
                        <td align="center" style="padding: 32px 32px 24px; border-bottom: 1px solid #F3F4F6;">
                            {$logoHtml}
                        </td>
                    </tr>
                    <!-- Main Body -->
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="margin: 0 0 16px; font-size: 18px; font-weight: 600; color: #111827;">{$greeting}</h2>
                            {$linesHtml}
                            {$actionHtml}
                            {$subtextHtml}
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 24px 32px; background-color: #F9FAFB; border-top: 1px solid #F3F4F6; font-size: 12px; color: #9CA3AF;">
                            <p style="margin: 0 0 4px;">&copy; {$currentYear} {$storeName}. All rights reserved.</p>
                            <p style="margin: 0;">This email was sent regarding your account on {$storeName}.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }
}
