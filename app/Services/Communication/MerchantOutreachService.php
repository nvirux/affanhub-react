<?php

declare(strict_types=1);

namespace App\Services\Communication;

class MerchantOutreachService
{
    /**
     * Format a raw phone number into standard international format for WhatsApp.
     * Defaulting to Nigeria (+234) if standard local format is provided.
     */
    public static function formatPhoneForWhatsApp(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Strip spaces, dashes, parentheses, plus signs
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (empty($cleaned)) {
            return null;
        }

        // Convert local Nigerian 080... / 070... / 090... / 081... to 23480...
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '234'.substr($cleaned, 1);
        }

        return $cleaned;
    }

    /**
     * Build an instant wa.me redirect URL.
     */
    public static function buildWhatsAppUrl(string $phone, string $message): string
    {
        $formattedPhone = self::formatPhoneForWhatsApp($phone) ?? $phone;

        return 'https://wa.me/'.$formattedPhone.'?text='.rawurlencode($message);
    }

    /**
     * Get pre-drafted outreach message templates.
     */
    public static function getTemplates(string $merchantName, ?string $storeName = null): array
    {
        $firstName = explode(' ', trim($merchantName))[0] ?: $merchantName;
        $storeText = $storeName ?: 'your store';

        return [
            'welcome_launch' => [
                'label' => '🚀 Welcome & Store Setup Walkthrough',
                'message' => "Hello {$firstName}, welcome to AffanHub! 🚀 I'm reaching out from the merchant onboarding team. I noticed you signed up but haven't launched your store yet. Would you like a quick walkthrough to help you get started?",
            ],
            'unfunded_capital' => [
                'label' => '💳 Wholesale Capital Funding Assistance',
                'message' => "Hello {$firstName}, your storefront ({$storeText}) is ready on AffanHub! Do you need any assistance funding your wholesale wallet so your customers can start purchasing data and airtime?",
            ],
            'domain_mobile_app' => [
                'label' => '📱 Custom Domain & Android App Setup',
                'message' => "Hello {$firstName}, regarding {$storeText} on AffanHub: did you know you can connect your own custom domain and download a free branded Android APK for your store customers? Let us know if you'd like us to assist you!",
            ],
            'general_checkin' => [
                'label' => '👋 General Check-in & Support',
                'message' => "Hello {$firstName}, checking in from the AffanHub merchant success team! How is everything going with your account? Please let us know if you need any assistance or have questions.",
            ],
        ];
    }

    /**
     * Get pre-drafted outreach message templates for end customers.
     */
    public static function getCustomerTemplates(string $customerName, ?string $storeName = null): array
    {
        $firstName = explode(' ', trim($customerName))[0] ?: $customerName;
        $storeText = $storeName ?: 'our platform';

        return [
            'kyc_verification' => [
                'label' => '🪪 KYC / BVN / NIN Verification Reminder',
                'message' => "Hello {$firstName}, this is customer support from {$storeText}. To unlock higher transaction limits and dedicated instant funding bank accounts, please take a moment to complete your BVN/NIN verification in your dashboard. Let us know if you need any assistance!",
            ],
            'low_balance_topup' => [
                'label' => '💳 Wallet Top-up & Dedicated Bank Accounts',
                'message' => "Hello {$firstName}, from {$storeText}! You can easily fund your wallet anytime using your dedicated virtual bank accounts for instant credit. Let us know if you need help with any of your orders!",
            ],
            'support_checkin' => [
                'label' => '👋 Customer Support Check-in',
                'message' => "Hello {$firstName}, checking in from {$storeText} customer support! Are you enjoying your experience or do you need assistance with data, airtime, or any other services? We are here to help!",
            ],
            'account_security' => [
                'label' => '🔐 Account Security & PIN Guidance',
                'message' => "Hello {$firstName}, from {$storeText}! For maximum security on your account, please remember to set a secure transaction PIN and enable 2FA in your settings. Please never share your PIN or OTP with anyone.",
            ],
        ];
    }

    /**
     * Get pre-drafted outreach message templates for domain setups.
     */
    public static function getDomainTemplates(string $ownerName, string $domainName, ?string $storeName = null): array
    {
        $firstName = explode(' ', trim($ownerName))[0] ?: $ownerName;
        $storeText = $storeName ?: 'your store';

        return [
            'dns_instructions' => [
                'label' => '🌐 DNS CNAME & TXT Configuration Guide',
                'message' => "Hello {$firstName}, regarding {$domainName} for {$storeText} on AffanHub: please ensure you create a CNAME record pointing to 'cname.affanhub.com'. Once added, your custom domain will activate automatically!",
            ],
            'domain_approved' => [
                'label' => '✅ Domain Approved & Routing Enabled',
                'message' => "Hello {$firstName}, great news! Your custom domain ({$domainName}) has been approved and traffic routing is now fully active for {$storeText}. Your customers can now access your store directly at https://{$domainName}.",
            ],
            'dns_issue' => [
                'label' => '⚠️ DNS Verification Issue Notice',
                'message' => "Hello {$firstName}, we checked {$domainName} for {$storeText} and noticed the DNS records are not yet resolving properly. Please check your domain DNS settings or reply here so we can assist you!",
            ],
        ];
    }
}
