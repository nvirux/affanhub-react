<?php

use App\Models\Feature;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Clean up old keys
        Service::where('key', 'cac_registration')->delete();
        Feature::where('slug', 'id_cac_registration')->delete();

        // 2. Create or update CAC Feature
        $feature = Feature::updateOrCreate(
            ['slug' => 'id_cac_services'],
            [
                'name' => 'CAC Services Access',
                'type' => 'boolean',
                'default_value' => 'true',
                'description' => 'Access to Corporate Affairs Commission business & company registrations and document services',
            ]
        );

        // 3. Create or update CAC Service
        Service::updateOrCreate(
            ['key' => 'cac_services'],
            [
                'name' => 'CAC Services',
                'category' => 'identity',
                'icon' => 'Briefcase',
                'description' => 'Register business names, companies, & get official CAC status reports',
                'feature_id' => $feature->id,
                'sort_order' => 14,
                'is_active' => true,
            ]
        );

        // 4. Grant access to all existing plans so it doesn't show "None / Locked"
        foreach (Plan::all() as $plan) {
            PlanFeature::updateOrCreate(
                [
                    'plan_id' => $plan->id,
                    'feature_id' => $feature->id,
                ],
                [
                    'value' => 'true',
                ]
            );
        }
    }

    public function down(): void
    {
        $feature = Feature::where('slug', 'id_cac_services')->first();
        if ($feature) {
            PlanFeature::where('feature_id', $feature->id)->delete();
            Service::where('key', 'cac_services')->delete();
            $feature->delete();
        }
    }
};
