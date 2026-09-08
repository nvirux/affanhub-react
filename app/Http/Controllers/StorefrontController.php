<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    /**
     * Display the Storefront Landing Page.
     */
    public function index(Request $request)
    {
        $store = tenant();
        $storeArray = $store ? $store->toArray() : [];

        // Normalize WhatsApp chat bubble phone number
        if (! empty($storeArray['whatsapp_chat_phone'])) {
            $phone = preg_replace('/\D/', '', $storeArray['whatsapp_chat_phone']);
            if (str_starts_with($phone, '0')) {
                $phone = '234'.substr($phone, 1);
            }
            $storeArray['whatsapp_chat_phone'] = $phone;
        }

        // Normalize social WhatsApp contact link
        if (! empty($storeArray['social_whatsapp'])) {
            $phone = preg_replace('/\D/', '', $storeArray['social_whatsapp']);
            if (str_starts_with($phone, '0')) {
                $phone = '234'.substr($phone, 1);
            }
            $storeArray['social_whatsapp'] = $phone;
        }

        if ($store && $store->logo_path) {
            $storeArray['logo_url'] = global_asset('storage/'.$store->logo_path);
        } else {
            $storeArray['logo_url'] = null;
        }

        return inertia('Storefront/Home', [
            'store' => $storeArray,
        ]);
    }
}
