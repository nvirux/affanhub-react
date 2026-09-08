<?php

namespace Database\Seeders;

use App\Models\DataPlan;
use App\Models\DataType;
use App\Models\Network;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Networks
        $mtn = Network::firstOrCreate(['slug' => 'mtn'], ['name' => 'MTN', 'is_active' => true, 'sort_order' => 1]);
        $airtel = Network::firstOrCreate(['slug' => 'airtel'], ['name' => 'Airtel', 'is_active' => true, 'sort_order' => 2]);
        $glo = Network::firstOrCreate(['slug' => 'glo'], ['name' => 'Glo', 'is_active' => true, 'sort_order' => 3]);
        $mobile9 = Network::firstOrCreate(['slug' => '9mobile'], ['name' => '9mobile', 'is_active' => true, 'sort_order' => 4]);

        // 2. Seed Data Types
        $sme = DataType::firstOrCreate(['slug' => 'sme'], ['name' => 'SME', 'is_active' => true]);
        $cg = DataType::firstOrCreate(['slug' => 'corporate'], ['name' => 'Corporate Gifting', 'is_active' => true]);
        $gifting = DataType::firstOrCreate(['slug' => 'gifting'], ['name' => 'Direct Gifting', 'is_active' => true]);

        // 3. Seed Sample Data Plans for MTN SME
        $plans = [
            [
                'network_id' => $mtn->id,
                'data_type_id' => $sme->id,
                'name' => '500 MB',
                'size_mb' => 512,
                'validity' => '30 Days',
                'cost_price' => 115.00,
                'selling_price' => 125.00,
                'default_retail_price' => 140.00,
                'plan_code' => 'MTN_SME_500MB',
                'is_active' => true,
            ],
            [
                'network_id' => $mtn->id,
                'data_type_id' => $sme->id,
                'name' => '1.0 GB',
                'size_mb' => 1024,
                'validity' => '30 Days',
                'cost_price' => 230.00,
                'selling_price' => 245.00,
                'default_retail_price' => 270.00,
                'plan_code' => 'MTN_SME_1GB',
                'is_active' => true,
            ],
            [
                'network_id' => $mtn->id,
                'data_type_id' => $sme->id,
                'name' => '2.0 GB',
                'size_mb' => 2048,
                'validity' => '30 Days',
                'cost_price' => 460.00,
                'selling_price' => 490.00,
                'default_retail_price' => 540.00,
                'plan_code' => 'MTN_SME_2GB',
                'is_active' => true,
            ],
            [
                'network_id' => $mtn->id,
                'data_type_id' => $sme->id,
                'name' => '5.0 GB',
                'size_mb' => 5120,
                'validity' => '30 Days',
                'cost_price' => 1150.00,
                'selling_price' => 1225.00,
                'default_retail_price' => 1350.00,
                'plan_code' => 'MTN_SME_5GB',
                'is_active' => true,
            ],
            // Airtel Corporate Gifting
            [
                'network_id' => $airtel->id,
                'data_type_id' => $cg->id,
                'name' => '1.0 GB',
                'size_mb' => 1024,
                'validity' => '30 Days',
                'cost_price' => 235.00,
                'selling_price' => 250.00,
                'default_retail_price' => 280.00,
                'plan_code' => 'AIRTEL_CG_1GB',
                'is_active' => true,
            ],
            [
                'network_id' => $airtel->id,
                'data_type_id' => $cg->id,
                'name' => '2.0 GB',
                'size_mb' => 2048,
                'validity' => '30 Days',
                'cost_price' => 470.00,
                'selling_price' => 500.00,
                'default_retail_price' => 560.00,
                'plan_code' => 'AIRTEL_CG_2GB',
                'is_active' => true,
            ],
        ];

        foreach ($plans as $p) {
            DataPlan::updateOrCreate(
                ['plan_code' => $p['plan_code']],
                $p
            );
        }
    }
}
