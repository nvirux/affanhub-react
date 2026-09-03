<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class PlanAndFeatureSeeder extends Seeder
{
    public function run(): void
    {
        // Create Features
        $features = [
            [
                'name' => 'Staff Limit',
                'slug' => 'staff_limit',
                'type' => 'integer',
                'default_value' => '2',
                'description' => 'Number of staff accounts the store can create.',
            ],
            [
                'name' => 'Custom Domain',
                'slug' => 'custom_domain',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows mapping store to a custom domain.',
            ],
            [
                'name' => 'API Access',
                'slug' => 'api_access',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows developer API integration.',
            ],
            [
                'name' => 'Custom Pricing Margins',
                'slug' => 'custom_pricing_margins',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Allows customizing VTU markup margins.',
            ],
        ];

        $featureModels = [];
        foreach ($features as $f) {
            $featureModels[$f['slug']] = Feature::updateOrCreate(['slug' => $f['slug']], $f);
        }

        // Create Plans (consolidated with price_monthly and price_yearly)
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Basic features to launch your store.',
                'price_monthly' => 0.00,
                'price_yearly' => 0.00,
                'trial_days' => 0,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Advanced features to scale your brand.',
                'price_monthly' => 5000.00,
                'price_yearly' => 50000.00,
                'trial_days' => 14,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Custom features for high-volume networks.',
                'price_monthly' => null,
                'price_yearly' => null,
                'trial_days' => 0,
            ],
        ];

        $planModels = [];
        foreach ($plans as $p) {
            $planModels[$p['slug']] = Plan::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // Map Features to Plans
        $mappings = [
            'starter' => [
                'staff_limit' => '2',
                'custom_domain' => 'false',
                'api_access' => 'false',
                'custom_pricing_margins' => 'false',
            ],
            'pro' => [
                'staff_limit' => '10',
                'custom_domain' => 'true',
                'api_access' => 'false',
                'custom_pricing_margins' => 'true',
            ],
            'enterprise' => [
                'staff_limit' => '999', // Representation of "unlimited"
                'custom_domain' => 'true',
                'api_access' => 'true',
                'custom_pricing_margins' => 'true',
            ],
        ];

        foreach ($mappings as $planSlug => $featureValues) {
            $plan = $planModels[$planSlug];
            foreach ($featureValues as $featureSlug => $value) {
                $feature = $featureModels[$featureSlug];
                PlanFeature::updateOrCreate(
                    ['plan_id' => $plan->id, 'feature_id' => $feature->id],
                    ['value' => $value]
                );
            }
        }
    }
}
