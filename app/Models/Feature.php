<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $table = 'features';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'default_value',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Standard platform capability presets for quick selection.
     */
    public static function getStandardFeaturePresets(): array
    {
        return [
            'staff_limit' => [
                'name' => 'Staff Limit',
                'type' => 'integer',
                'default_value' => '2',
                'description' => 'Maximum number of staff accounts the store can create.',
            ],
            'custom_domain' => [
                'name' => 'Custom Domain',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows mapping store to a custom domain with SSL.',
            ],
            'api_access' => [
                'name' => 'API Access',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows merchant developer API integration and webhook endpoints.',
            ],
            'custom_pricing_margins' => [
                'name' => 'Custom Pricing Margins',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows customizing VTU and service markup profit margins.',
            ],
            'referral_system' => [
                'name' => 'Referral & Earn System',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows store to activate customer referral and earning programs.',
            ],
            'custom_branding' => [
                'name' => 'Custom Branding & Theming',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows custom HEX brand colors, custom favicon upload, and web chat widgets.',
            ],
            'custom_email' => [
                'name' => 'Custom Email (Resend)',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows connecting a custom Resend account to send emails from the store domain.',
            ],
            'mobile_app' => [
                'name' => 'Mobile App (APK Builder)',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows compiling custom branded Android APK applications for customers.',
            ],
        ];
    }
}
