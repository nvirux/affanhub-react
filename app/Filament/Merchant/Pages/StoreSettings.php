<?php

namespace App\Filament\Merchant\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use UnitEnum;
use BackedEnum;

use Livewire\WithFileUploads;

class StoreSettings extends Page
{
    use WithFileUploads;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Store Settings';

    protected static ?string $navigationLabel = 'Store Settings';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.merchant.pages.store-settings';

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
        
        $this->logoPath = $store->logo_path ?? '';
        $this->whatsappChatEnabled = (bool)($store->whatsapp_chat_enabled ?? false);
        $this->whatsappChatPhone = $store->whatsapp_chat_phone ?? '';
        $this->whatsappChatMessage = $store->whatsapp_chat_message ?? '';
        $this->tawkChatEnabled = (bool)($store->tawk_chat_enabled ?? false);
        $this->tawkPropertyId = $store->tawk_property_id ?? '';
        $this->tawkWidgetId = $store->tawk_widget_id ?? '';
    }

    public function removeLogo()
    {
        $store = Filament::getTenant();
        
        if ($store->logo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($store->logo_path);
            
            $oldValues = [
                'logo_path' => $store->logo_path,
            ];

            $store->logo_path = null;
            $store->save();
            $this->logoPath = '';
            
            \App\Services\Audit\ActivityLogger::log(
                'store_settings_updated', 
                "Removed storefront logo",
                [
                    'changes' => [
                        'logo_path' => [
                            'old' => $oldValues['logo_path'],
                            'new' => null,
                        ]
                    ]
                ]
            );

            Notification::make()
                ->title('Logo Removed')
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
            
            'logo' => 'nullable|image|max:1024', // max 1MB
            'whatsappChatPhone' => 'nullable|string',
            'whatsappChatMessage' => 'nullable|string|max:200',
            'tawkPropertyId' => 'nullable|string',
            'tawkWidgetId' => 'nullable|string',
        ]);

        $store = Filament::getTenant();

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
            'logo_path' => $store->logo_path,
            'whatsapp_chat_enabled' => (bool)($store->whatsapp_chat_enabled ?? false),
            'whatsapp_chat_phone' => $store->whatsapp_chat_phone,
            'whatsapp_chat_message' => $store->whatsapp_chat_message,
            'tawk_chat_enabled' => (bool)($store->tawk_chat_enabled ?? false),
            'tawk_property_id' => $store->tawk_property_id,
            'tawk_widget_id' => $store->tawk_widget_id,
        ];

        // Handle logo upload
        if ($this->logo) {
            $tenantId = $store->id;
            $path = $this->logo->store("logos/{$tenantId}", 'public');
            $store->logo_path = $path;
            $this->logoPath = $path;
            $this->logo = null; // Clear livewire file property
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
        
        $store->whatsapp_chat_enabled = $this->whatsappChatEnabled;
        $store->whatsapp_chat_phone = $this->whatsappChatPhone;
        $store->whatsapp_chat_message = $this->whatsappChatMessage;
        $store->tawk_chat_enabled = $this->tawkChatEnabled;
        $store->tawk_property_id = $this->tawkPropertyId;
        $store->tawk_widget_id = $this->tawkWidgetId;
        
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
            'logo_path' => $store->logo_path,
            'whatsapp_chat_enabled' => (bool)($store->whatsapp_chat_enabled ?? false),
            'whatsapp_chat_phone' => $store->whatsapp_chat_phone,
            'whatsapp_chat_message' => $store->whatsapp_chat_message,
            'tawk_chat_enabled' => (bool)($store->tawk_chat_enabled ?? false),
            'tawk_property_id' => $store->tawk_property_id,
            'tawk_widget_id' => $store->tawk_widget_id,
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
        if (!empty($changes)) {
            \App\Services\Audit\ActivityLogger::log(
                'store_settings_updated', 
                "Updated storefront metadata and customer support settings",
                ['changes' => $changes]
            );
        }

        Notification::make()
            ->title('Settings Saved')
            ->body('Your storefront settings have been updated successfully.')
            ->success()
            ->send();
    }
}
