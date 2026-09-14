<?php

namespace Database\Seeders;

use App\Models\AirtimeDiscount;
use App\Models\Network;
use Illuminate\Database\Seeder;

class AirtimeDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'mtn' => [
                'buy_discount' => 3.00,
                'default_merchant_discount' => 2.00,
                'default_retail_discount' => 1.50,
            ],
            'airtel' => [
                'buy_discount' => 3.50,
                'default_merchant_discount' => 2.50,
                'default_retail_discount' => 2.00,
            ],
            'glo' => [
                'buy_discount' => 4.50,
                'default_merchant_discount' => 3.50,
                'default_retail_discount' => 3.00,
            ],
            '9mobile' => [
                'buy_discount' => 5.00,
                'default_merchant_discount' => 4.00,
                'default_retail_discount' => 3.50,
            ],
        ];

        $networkInfo = [
            'mtn' => ['name' => 'MTN', 'sort_order' => 1],
            'airtel' => ['name' => 'Airtel', 'sort_order' => 2],
            'glo' => ['name' => 'Glo', 'sort_order' => 3],
            '9mobile' => ['name' => '9mobile', 'sort_order' => 4],
        ];

        foreach ($defaults as $slug => $data) {
            $network = Network::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $networkInfo[$slug]['name'] ?? strtoupper($slug),
                    'is_active' => true,
                    'sort_order' => $networkInfo[$slug]['sort_order'] ?? 99,
                ]
            );

            AirtimeDiscount::updateOrCreate(
                ['network_id' => $network->id],
                [
                    'buy_discount' => $data['buy_discount'],
                    'default_merchant_discount' => $data['default_merchant_discount'],
                    'default_retail_discount' => $data['default_retail_discount'],
                    'min_amount' => 50.00,
                    'max_amount' => 50000.00,
                    'is_active' => true,
                ]
            );
        }
    }
}
