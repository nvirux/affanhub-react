<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Features for Plan Gating
        $features = [
            'vtu_education' => Feature::firstOrCreate(['slug' => 'vtu_education'], [
                'name' => 'Education Services Access',
                'type' => 'boolean',
                'default_value' => 'true',
                'description' => 'Access to buy WAEC/NECO/JAMB PINs & Education services',
            ]),
            'vtu_airtime_cash' => Feature::firstOrCreate(['slug' => 'vtu_airtime_cash'], [
                'name' => 'Airtime to Cash Access',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Access to airtime to cash conversion service',
            ]),
            'vtu_bulk_sms' => Feature::firstOrCreate(['slug' => 'vtu_bulk_sms'], [
                'name' => 'Bulk SMS Access',
                'type' => 'boolean',
                'default_value' => 'true',
                'description' => 'Access to Bulk SMS portal',
            ]),
            'id_nin_verification' => Feature::firstOrCreate(['slug' => 'id_nin_verification'], [
                'name' => 'NIN Verification Access',
                'type' => 'boolean',
                'default_value' => 'true',
                'description' => 'Access to NIN verification & slip search',
            ]),
            'id_bvn_verification' => Feature::firstOrCreate(['slug' => 'id_bvn_verification'], [
                'name' => 'BVN Verification Access',
                'type' => 'boolean',
                'default_value' => 'true',
                'description' => 'Access to BVN verification portal',
            ]),
            'id_nin_modification' => Feature::firstOrCreate(['slug' => 'id_nin_modification'], [
                'name' => 'NIN Modification Access',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Access to NIN bio-data modification',
            ]),
            'id_bvn_modification' => Feature::firstOrCreate(['slug' => 'id_bvn_modification'], [
                'name' => 'BVN Modification Access',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Access to BVN bio-data modification',
            ]),
            'id_nin_validation' => Feature::firstOrCreate(['slug' => 'id_nin_validation'], [
                'name' => 'NIN Validation Access',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Access to NIN status validation',
            ]),
            'id_ipe_clearance' => Feature::firstOrCreate(['slug' => 'id_ipe_clearance'], [
                'name' => 'IPE Clearance Access',
                'type' => 'boolean',
                'default_value' => 'false',
                'description' => 'Access to IPE clearance certificates',
            ]),
        ];

        // Delete old key exam_pins if exists
        Service::where('key', 'exam_pins')->delete();

        // 2. Register Services
        $services = [
            // VTU Services
            [
                'name' => 'Airtime Topup',
                'key' => 'airtime',
                'category' => 'vtu',
                'icon' => 'Smartphone',
                'description' => 'Top up airtime for all networks',
                'feature_id' => null, // Available to all plans
                'sort_order' => 1,
            ],
            [
                'name' => 'Data Bundle',
                'key' => 'data',
                'category' => 'vtu',
                'icon' => 'Wifi',
                'description' => 'Buy cheap data bundles',
                'feature_id' => null, // Available to all plans
                'sort_order' => 2,
            ],
            [
                'name' => 'Cable TV',
                'key' => 'cable',
                'category' => 'vtu',
                'icon' => 'Tv',
                'description' => 'DSTV, GOTV & Startimes payments',
                'feature_id' => null, // Available to all plans
                'sort_order' => 3,
            ],
            [
                'name' => 'Electricity Bills',
                'key' => 'electricity',
                'category' => 'vtu',
                'icon' => 'Zap',
                'description' => 'Buy electricity tokens',
                'feature_id' => null, // Available to all plans
                'sort_order' => 4,
            ],
            [
                'name' => 'Education',
                'key' => 'education',
                'category' => 'vtu',
                'icon' => 'GraduationCap',
                'description' => 'Buy WAEC, NECO & JAMB PINs',
                'feature_id' => $features['vtu_education']->id,
                'sort_order' => 5,
            ],
            [
                'name' => 'Airtime to Cash',
                'key' => 'airtime_cash',
                'category' => 'vtu',
                'icon' => 'RefreshCw',
                'description' => 'Convert excess airtime to bank cash',
                'feature_id' => $features['vtu_airtime_cash']->id,
                'sort_order' => 6,
            ],
            [
                'name' => 'Bulk SMS',
                'key' => 'bulk_sms',
                'category' => 'vtu',
                'icon' => 'MessageSquare',
                'description' => 'Send customized bulk SMS',
                'feature_id' => $features['vtu_bulk_sms']->id,
                'sort_order' => 7,
            ],

            // Identity Services
            [
                'name' => 'NIN Verification',
                'key' => 'nin_verification',
                'category' => 'identity',
                'icon' => 'IdCard',
                'description' => 'Verify NIN details & print slips',
                'feature_id' => $features['id_nin_verification']->id,
                'sort_order' => 8,
            ],
            [
                'name' => 'BVN Verification',
                'key' => 'bvn_verification',
                'category' => 'identity',
                'icon' => 'UserCheck',
                'description' => 'Verify BVN details & identity',
                'feature_id' => $features['id_bvn_verification']->id,
                'sort_order' => 9,
            ],
            [
                'name' => 'NIN Modification',
                'key' => 'nin_modification',
                'category' => 'identity',
                'icon' => 'Edit3',
                'description' => 'Update NIN name, DOB or phone number',
                'feature_id' => $features['id_nin_modification']->id,
                'sort_order' => 10,
            ],
            [
                'name' => 'BVN Modification',
                'key' => 'bvn_modification',
                'category' => 'identity',
                'icon' => 'UserCheck',
                'description' => 'Update BVN details & records',
                'feature_id' => $features['id_bvn_modification']->id,
                'sort_order' => 11,
            ],
            [
                'name' => 'NIN Validation',
                'key' => 'nin_validation',
                'category' => 'identity',
                'icon' => 'ShieldCheck',
                'description' => 'Validate NIN status & clear issues',
                'feature_id' => $features['id_nin_validation']->id,
                'sort_order' => 12,
            ],
            [
                'name' => 'IPE Clearance',
                'key' => 'ipe_clearance',
                'category' => 'identity',
                'icon' => 'FileCheck',
                'description' => 'Process IPE clearance certificates',
                'feature_id' => $features['id_ipe_clearance']->id,
                'sort_order' => 13,
            ],
        ];

        foreach ($services as $svcData) {
            Service::updateOrCreate(
                ['key' => $svcData['key']],
                $svcData
            );
        }
    }
}
