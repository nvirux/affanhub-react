<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Slip;
use Illuminate\Database\Seeder;

class SlipSeeder extends Seeder
{
    /**
     * Seed official identity slips for NIN and BVN services.
     */
    public function run(): void
    {
        $ninService = Service::firstOrCreate(
            ['key' => 'nin_verification'],
            [
                'name' => 'NIN Verification',
                'category' => 'Identity Services',
                'description' => 'Verify National Identification Numbers and retrieve verified slips.',
                'is_active' => true,
            ]
        );
        if ($ninService) {
            $ninSlips = [
                [
                    'slug' => 'information',
                    'name' => 'Information Slip',
                    'badge' => 'Text Summary',
                    'description' => 'Official verified biodata details with tracking ID.',
                    'features' => ['Tracking ID Verification', 'Full Bio-Data Details', 'Print-Ready Summary'],
                    'color' => 'slate',
                    'is_popular' => false,
                    'cost_price' => 30.00,
                    'selling_price' => 50.00,
                    'sort_order' => 1,
                ],
                [
                    'slug' => 'regular',
                    'name' => 'Regular Slip',
                    'badge' => 'Pocket Slip',
                    'description' => 'Compact verification slip with tracking ID and photo summary.',
                    'features' => ['Photo & Bio-Data', 'Tracking ID', 'Compact Size'],
                    'color' => 'blue',
                    'is_popular' => false,
                    'cost_price' => 60.00,
                    'selling_price' => 100.00,
                    'sort_order' => 2,
                ],
                [
                    'slug' => 'standard',
                    'name' => 'Standard Slip',
                    'badge' => 'Official A4',
                    'description' => 'Full-page document with digital verification stamp and QR code.',
                    'features' => ['Full Bio-Data', 'Verified QR Code', 'Print-Ready A4'],
                    'color' => 'emerald',
                    'is_popular' => false,
                    'cost_price' => 80.00,
                    'selling_price' => 150.00,
                    'sort_order' => 3,
                ],
                [
                    'slug' => 'premium',
                    'name' => 'Premium Card',
                    'badge' => 'Plastic ID',
                    'description' => 'Front & back card format with high-res portrait and scannable barcode.',
                    'features' => ['Front & Back Card', 'High-Res Photo', 'Scannable Barcode'],
                    'color' => 'amber',
                    'is_popular' => true,
                    'cost_price' => 150.00,
                    'selling_price' => 300.00,
                    'sort_order' => 4,
                ],
            ];

            foreach ($ninSlips as $slipData) {
                Slip::updateOrCreate(
                    [
                        'service_id' => $ninService->id,
                        'slug' => $slipData['slug'],
                    ],
                    $slipData
                );
            }
        }

        $bvnService = Service::firstOrCreate(
            ['key' => 'bvn_verification'],
            [
                'name' => 'BVN Verification',
                'category' => 'Identity Services',
                'description' => 'Verify Bank Verification Numbers and retrieve verified slips.',
                'is_active' => true,
            ]
        );
        if ($bvnService) {
            $bvnSlips = [
                [
                    'slug' => 'basic',
                    'name' => 'Basic Slip',
                    'badge' => 'Summary',
                    'description' => 'Summary BVN verification slip with banking bio-data details.',
                    'features' => ['Name & Phone Verification', 'DOB & Gender', 'Verification Reference'],
                    'color' => 'blue',
                    'is_popular' => false,
                    'cost_price' => 30.00,
                    'selling_price' => 50.00,
                    'sort_order' => 1,
                ],
                [
                    'slug' => 'advance',
                    'name' => 'Advance Slip',
                    'badge' => 'Full Sheet',
                    'description' => 'Comprehensive A4 banking verification document with digital verification stamp.',
                    'features' => ['Complete Bio-Data', 'Bank Account Linking Details', 'Print-Ready A4'],
                    'color' => 'emerald',
                    'is_popular' => true,
                    'cost_price' => 60.00,
                    'selling_price' => 100.00,
                    'sort_order' => 2,
                ],
                [
                    'slug' => 'plastic',
                    'name' => 'Plastic Card',
                    'badge' => 'ID Card',
                    'description' => 'Front & back BVN identification card format with barcode and photo.',
                    'features' => ['Front & Back Design', 'Scannable Barcode', 'Customer Photo Integration'],
                    'color' => 'amber',
                    'is_popular' => false,
                    'cost_price' => 120.00,
                    'selling_price' => 200.00,
                    'sort_order' => 3,
                ],
            ];

            foreach ($bvnSlips as $slipData) {
                Slip::updateOrCreate(
                    [
                        'service_id' => $bvnService->id,
                        'slug' => $slipData['slug'],
                    ],
                    $slipData
                );
            }
        }
    }
}
