<?php

namespace App\Filament\Merchant\Pages;

use App\Services\Audit\ActivityLogger;
use App\Services\Branding\ColorHelper;
use App\Services\Mail\TenantMailService;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use UnitEnum;

class StoreSettings extends Page
{
    use WithFileUploads;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Store Settings';

    protected static ?string $navigationLabel = 'Store Settings';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.merchant.pages.store-settings';

    // Active Tab Navigation
    public string $activeTab = 'profile';

    // Whitelisted form fields
    public ?string $name = '';

    public ?string $description = '';

    public ?string $dashboardSubtitle = '';

    public ?string $contactEmail = '';

    public ?string $contactPhone = '';

    // Social Links
    public ?string $socialInstagram = '';

    public ?string $socialFacebook = '';

    public ?string $socialWhatsapp = '';

    // Brand Colors & Favicon
    public ?string $primaryColor = ColorHelper::DEFAULT_HEX;

    public $favicon;

    public ?string $faviconPath = '';

    // Logo Upload
    public $logo;

    public ?string $logoPath = '';

    // Live Support Settings
    public bool $whatsappChatEnabled = false;

    public ?string $whatsappChatPhone = '';

    public ?string $whatsappChatMessage = '';

    public bool $tawkChatEnabled = false;

    public ?string $tawkPropertyId = '';

    public ?string $tawkWidgetId = '';

    // Resend Email Integration
    public ?string $resendApiKey = '';

    public ?string $resendFromEmail = '';

    public ?string $resendFromName = '';

    public ?string $testEmailRecipient = '';

    public function mount()
    {
        $store = Filament::getTenant();

        $this->name = $store->name;
        $this->description = $store->description ?? '';
        $this->dashboardSubtitle = $store->dashboard_subtitle ?? '';
        $this->contactEmail = $store->contact_email ?? '';
        $this->contactPhone = $store->contact_phone ?? '';
        $this->socialInstagram = $store->social_instagram ?? '';
        $this->socialFacebook = $store->social_facebook ?? '';
        $this->socialWhatsapp = $store->social_whatsapp ?? '';

        $this->primaryColor = $store->primary_color ?? ColorHelper::DEFAULT_HEX;
        $this->faviconPath = $store->favicon_path ?? '';

        $this->logoPath = $store->logo_path ?? '';
        $this->whatsappChatEnabled = (bool) ($store->whatsapp_chat_enabled ?? false);
        $this->whatsappChatPhone = $store->whatsapp_chat_phone ?? '';
        $this->whatsappChatMessage = $store->whatsapp_chat_message ?? '';
        $this->tawkChatEnabled = (bool) ($store->tawk_chat_enabled ?? false);
        $this->tawkPropertyId = $store->tawk_property_id ?? '';
        $this->tawkWidgetId = $store->tawk_widget_id ?? '';

        $this->resendApiKey = $store->resend_api_key ?? '';
        $this->resendFromEmail = $store->resend_from_email ?? '';
        $this->resendFromName = $store->resend_from_name ?? '';
        $this->testEmailRecipient = $store->contact_email ?? '';
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function selectPreset(string $hex): void
    {
        $this->primaryColor = ColorHelper::normalizeHex($hex);
    }

    public function removeLogo()
    {
        $store = Filament::getTenant();

        if ($store->logo_path) {
            Storage::delete($store->logo_path);
            Storage::disk('public')->delete($store->logo_path);

            $oldValues = [
                'logo_path' => $store->logo_path,
            ];

            $store->logo_path = null;
            $store->save();
            $this->logoPath = '';

            ActivityLogger::log(
                'store_settings_updated',
                'Removed storefront logo',
                [
                    'changes' => [
                        'logo_path' => [
                            'old' => $oldValues['logo_path'],
                            'new' => null,
                        ],
                    ],
                ]
            );

            Notification::make()
                ->title('Logo Removed')
                ->success()
                ->send();
        }
    }

    public function removeFavicon()
    {
        $store = Filament::getTenant();

        if ($store->favicon_path) {
            Storage::delete($store->favicon_path);
            Storage::disk('public')->delete($store->favicon_path);

            $oldFavicon = $store->favicon_path;
            $store->favicon_path = null;
            $store->save();
            $this->faviconPath = '';

            ActivityLogger::log(
                'store_settings_updated',
                'Removed storefront favicon',
                [
                    'changes' => [
                        'favicon_path' => [
                            'old' => $oldFavicon,
                            'new' => null,
                        ],
                    ],
                ]
            );

            Notification::make()
                ->title('Favicon Removed')
                ->success()
                ->send();
        }
    }

    public function saveSettings()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:100',
            'contactEmail' => 'nullable|email',
            'contactPhone' => 'nullable|string',
            'description' => 'nullable|string|max:500',
            'dashboardSubtitle' => 'nullable|string|max:40',
            'socialInstagram' => 'nullable|string',
            'socialFacebook' => 'nullable|string',
            'socialWhatsapp' => 'nullable|string',

            'primaryColor' => 'required|string',
            'favicon' => 'nullable|file|mimes:png,ico,svg|max:512',
            'logo' => 'nullable|image|max:1024', // max 1MB
            'whatsappChatPhone' => 'nullable|string',
            'whatsappChatMessage' => 'nullable|string|max:200',
            'tawkPropertyId' => 'nullable|string',
            'tawkWidgetId' => 'nullable|string',
        ]);

        if (! ColorHelper::isValidHex($this->primaryColor)) {
            $this->addError('primaryColor', 'Please enter a valid 6-character hex color code (e.g. #2563EB).');

            return;
        }

        if (! ColorHelper::isAllowed($this->primaryColor)) {
            $this->addError('primaryColor', 'White or very light colors cannot be used as primary brand color so buttons and text remain clear.');

            return;
        }

        $store = Filament::getTenant();
        $hasCustomBranding = $store->hasFeature('custom_branding');

        if (! $hasCustomBranding && ColorHelper::findMatchingPreset($this->primaryColor) === null) {
            $this->addError('primaryColor', 'Custom HEX color codes require a Pro or Enterprise plan. Please select one of the curated presets or upgrade your plan.');

            return;
        }

        if (! $hasCustomBranding && $this->favicon) {
            $this->addError('favicon', 'Custom browser favicon upload requires a Pro or Enterprise plan.');

            return;
        }

        if (! $hasCustomBranding && $this->tawkChatEnabled) {
            $this->addError('tawkChatEnabled', 'Tawk.to Live Chat requires a Pro or Enterprise plan.');

            return;
        }

        $hasCustomEmail = $store->hasFeature('custom_email');

        if (! empty($this->resendApiKey) || ! empty($this->resendFromEmail)) {
            if (! $hasCustomEmail) {
                $this->addError('resendApiKey', 'Connecting a custom Resend account requires a Pro or Enterprise plan.');

                return;
            }

            if (empty($this->resendFromEmail) || ! filter_var($this->resendFromEmail, FILTER_VALIDATE_EMAIL)) {
                $this->addError('resendFromEmail', 'Please enter a valid sender email address (e.g. support@yourdomain.com).');

                return;
            }
        }

        // Capture original settings for delta/change logging
        $oldValues = [
            'name' => $store->name,
            'description' => $store->description,
            'dashboard_subtitle' => $store->dashboard_subtitle,
            'contact_email' => $store->contact_email,
            'contact_phone' => $store->contact_phone,
            'social_instagram' => $store->social_instagram,
            'social_facebook' => $store->social_facebook,
            'social_whatsapp' => $store->social_whatsapp,
            'primary_color' => $store->primary_color,
            'favicon_path' => $store->favicon_path,
            'logo_path' => $store->logo_path,
            'whatsapp_chat_enabled' => (bool) ($store->whatsapp_chat_enabled ?? false),
            'whatsapp_chat_phone' => $store->whatsapp_chat_phone,
            'whatsapp_chat_message' => $store->whatsapp_chat_message,
            'tawk_chat_enabled' => (bool) ($store->tawk_chat_enabled ?? false),
            'tawk_property_id' => $store->tawk_property_id,
            'tawk_widget_id' => $store->tawk_widget_id,
            'resend_from_email' => $store->resend_from_email,
            'resend_from_name' => $store->resend_from_name,
        ];

        // Handle logo upload
        if ($this->logo) {
            if ($store->logo_path) {
                Storage::delete($store->logo_path);
                Storage::disk('public')->delete($store->logo_path);
            }
            $tenantId = $store->id;
            $path = $this->logo->store("logos/{$tenantId}");
            $store->logo_path = $path;
            $this->logoPath = $path;
            $this->logo = null; // Clear livewire file property
        }

        // Handle favicon upload
        if ($this->favicon) {
            if ($store->favicon_path) {
                Storage::delete($store->favicon_path);
                Storage::disk('public')->delete($store->favicon_path);
            }
            $tenantId = $store->id;
            $path = $this->favicon->store("favicons/{$tenantId}");
            $store->favicon_path = $path;
            $this->faviconPath = $path;
            $this->favicon = null; // Clear livewire file property
        }

        // Save core and dynamic fields directly on the tenant model
        $store->name = $this->name;
        $store->description = $this->description;
        $store->dashboard_subtitle = $this->dashboardSubtitle;
        $store->contact_email = $this->contactEmail;
        $store->contact_phone = $this->contactPhone;
        $store->social_instagram = $this->socialInstagram;
        $store->social_facebook = $this->socialFacebook;
        $store->social_whatsapp = $this->socialWhatsapp;

        $store->primary_color = ColorHelper::normalizeHex($this->primaryColor);

        $store->whatsapp_chat_enabled = $this->whatsappChatEnabled;
        $store->whatsapp_chat_phone = $this->whatsappChatPhone;
        $store->whatsapp_chat_message = $this->whatsappChatMessage;
        $store->tawk_chat_enabled = $this->tawkChatEnabled;
        $store->tawk_property_id = $this->tawkPropertyId;
        $store->tawk_widget_id = $this->tawkWidgetId;

        // Resend email settings
        $store->resend_api_key = $this->resendApiKey ?: null;
        $store->resend_from_email = $this->resendFromEmail ?: null;
        $store->resend_from_name = $this->resendFromName ?: null;

        $store->save();

        $newValues = [
            'name' => $this->name,
            'description' => $this->description,
            'dashboard_subtitle' => $this->dashboardSubtitle,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
            'social_instagram' => $this->socialInstagram,
            'social_facebook' => $this->socialFacebook,
            'social_whatsapp' => $this->socialWhatsapp,
            'primary_color' => $store->primary_color,
            'favicon_path' => $store->favicon_path,
            'logo_path' => $store->logo_path,
            'whatsapp_chat_enabled' => (bool) ($store->whatsapp_chat_enabled ?? false),
            'whatsapp_chat_phone' => $store->whatsapp_chat_phone,
            'whatsapp_chat_message' => $store->whatsapp_chat_message,
            'tawk_chat_enabled' => (bool) ($store->tawk_chat_enabled ?? false),
            'tawk_property_id' => $store->tawk_property_id,
            'tawk_widget_id' => $store->tawk_widget_id,
            'resend_from_email' => $store->resend_from_email,
            'resend_from_name' => $store->resend_from_name,
        ];

        // Calculate changes
        $changes = [];
        foreach ($newValues as $key => $val) {
            $oldVal = $oldValues[$key] ?? '';
            $newVal = $val ?? '';
            if ($oldVal !== $newVal) {
                $changes[$key] = [
                    'old' => $oldVal,
                    'new' => $newVal,
                ];
            }
        }

        // Write an audit log entry for this action
        if (! empty($changes)) {
            ActivityLogger::log(
                'store_settings_updated',
                'Updated storefront branding and settings',
                ['changes' => $changes]
            );
        }

        Notification::make()
            ->title('Settings Saved')
            ->body('Your storefront settings and theme have been updated successfully.')
            ->success()
            ->send();
    }

    public function sendTestEmail(): void
    {
        $store = Filament::getTenant();

        if (! $store->hasFeature('custom_email')) {
            Notification::make()
                ->title('Plan Upgrade Required')
                ->body('Custom Email integration requires a Pro or Enterprise plan.')
                ->danger()
                ->send();

            return;
        }

        if (empty($this->resendApiKey)) {
            $this->addError('resendApiKey', 'Please enter your Resend API Key first.');

            return;
        }

        if (empty($this->resendFromEmail) || ! filter_var($this->resendFromEmail, FILTER_VALIDATE_EMAIL)) {
            $this->addError('resendFromEmail', 'Please enter a valid sender email address.');

            return;
        }

        if (empty($this->testEmailRecipient) || ! filter_var($this->testEmailRecipient, FILTER_VALIDATE_EMAIL)) {
            $this->addError('testEmailRecipient', 'Please enter a valid email address to receive the test email.');

            return;
        }

        $mailService = app(TenantMailService::class);
        $result = $mailService->sendTestEmail(
            store: $store,
            apiKey: $this->resendApiKey,
            fromEmail: $this->resendFromEmail,
            fromName: $this->resendFromName,
            recipient: $this->testEmailRecipient
        );

        if ($result['success']) {
            Notification::make()
                ->title('Test Email Sent!')
                ->body($result['message'])
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Test Email Failed')
                ->body($result['error'])
                ->danger()
                ->send();
        }
    }
}
