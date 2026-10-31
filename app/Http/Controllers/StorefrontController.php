<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorefrontController extends Controller
{
    /**
     * Display the Storefront Landing Page.
     */
    public function index(Request $request)
    {
        $store = tenant();

        // Normalize WhatsApp chat bubble phone number
        $whatsappChatPhone = null;
        if (! empty($store?->whatsapp_chat_phone)) {
            $phone = preg_replace('/\D/', '', $store->whatsapp_chat_phone);
            if (str_starts_with($phone, '0')) {
                $phone = '234'.substr($phone, 1);
            }
            $whatsappChatPhone = $phone;
        }

        // Normalize social WhatsApp contact link (preserves group links or formats phone numbers)
        $socialWhatsapp = null;
        if (! empty($store?->social_whatsapp)) {
            $val = trim($store->social_whatsapp);
            if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
                $socialWhatsapp = $val;
            } else {
                $phone = preg_replace('/\D/', '', $val);
                if (str_starts_with($phone, '0')) {
                    $phone = '234'.substr($phone, 1);
                }
                $socialWhatsapp = ! empty($phone) ? "https://wa.me/{$phone}" : null;
            }
        }

        $template = $store?->landing_page_template ?? 'default';

        // Strictly whitelist only safe public visual branding fields
        $publicStore = [
            'name' => $store?->name ?? 'Store',
            'description' => $store?->description ?? '',
            'contact_email' => $store?->contact_email ?? '',
            'contact_phone' => $store?->contact_phone ?? '',
            'contact_phone_2' => $store?->contact_phone_2 ?? '',
            'primary_color' => $store?->primary_color ?? '#F97316',
            'dashboard_subtitle' => $store?->dashboard_subtitle ?? '',
            'logo_url' => ($store && $store->logo_path) ? Storage::url($store->logo_path) : null,
            'favicon_url' => ($store && $store->favicon_path) ? Storage::url($store->favicon_path) : null,
            'whatsapp_chat_enabled' => ! empty($whatsappChatPhone),
            'whatsapp_chat_phone' => $whatsappChatPhone,
            'whatsapp_chat_message' => $store?->whatsapp_chat_message ?? 'Hello',
            'social_whatsapp' => $socialWhatsapp,
            'social_instagram' => $store?->social_instagram ?? '',
            'social_facebook' => $store?->social_facebook ?? '',
            'landing_page_template' => $template,
        ];

        $component = match ($template) {
            'classic', 'legacy', 'v1' => 'Storefront/Themes/Classic',
            'cobalt' => 'Storefront/Themes/Cobalt',
            'vantage' => 'Storefront/Themes/Vantage',
            'refract' => 'Storefront/Themes/Refract',
            'default', 'yushpay' => 'Storefront/Themes/Default',
            default => 'Storefront/Themes/Default',
        };

        return inertia($component, [
            'store' => $publicStore,
        ]);
    }
}
