<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('features', 'sort_order')) {
            Schema::table('features', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('description');
            });
        }

        // Dissociate legacy feature_id on services
        DB::table('services')->update(['feature_id' => null]);

        // Delete obsolete service-specific ghost features
        $serviceSlugs = [
            'vtu_education',
            'vtu_airtime_cash',
            'vtu_bulk_sms',
            'id_nin_verification',
            'id_bvn_verification',
            'id_nin_modification',
            'id_bvn_modification',
            'id_nin_validation',
            'id_ipe_clearance',
            'id_cac_services',
            'id_cac_registration',
        ];

        $ghostFeatureIds = DB::table('features')
            ->whereIn('slug', $serviceSlugs)
            ->orWhere('slug', 'like', 'vtu_%')
            ->orWhere('slug', 'like', 'id_%')
            ->orWhere('slug', 'like', 'test_feat%')
            ->pluck('id')
            ->toArray();

        if (! empty($ghostFeatureIds)) {
            DB::table('plan_features')->whereIn('feature_id', $ghostFeatureIds)->delete();
            DB::table('store_feature_overrides')->whereIn('feature_id', $ghostFeatureIds)->delete();
            DB::table('features')->whereIn('id', $ghostFeatureIds)->delete();
        }

        // Standard Platform Features with ordered sequence
        $standardFeatures = [
            [
                'name' => 'Staff Limit',
                'slug' => 'staff_limit',
                'type' => 'integer',
                'default_value' => '2',
                'description' => 'Maximum number of staff accounts the store can create.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Custom Domain',
                'slug' => 'custom_domain',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows mapping store to a custom domain with SSL.',
                'sort_order' => 2,
            ],
            [
                'name' => 'API Access',
                'slug' => 'api_access',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows merchant developer API integration and webhook endpoints.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Custom Pricing Margins',
                'slug' => 'custom_pricing_margins',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows customizing VTU and service markup profit margins.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Referral & Earn System',
                'slug' => 'referral_system',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows store to activate customer referral and earning programs.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Custom Branding & Theming',
                'slug' => 'custom_branding',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows custom HEX brand colors, custom favicon upload, and web chat widgets.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Custom Email (Resend)',
                'slug' => 'custom_email',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows connecting a custom Resend account to send emails from the store domain.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Mobile App (APK Builder)',
                'slug' => 'mobile_app',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows compiling custom branded Android APK applications for customers.',
                'sort_order' => 8,
            ],
        ];

        foreach ($standardFeatures as $sf) {
            DB::table('features')->updateOrInsert(
                ['slug' => $sf['slug']],
                array_merge($sf, [
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('features', 'sort_order')) {
            Schema::table('features', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
};
