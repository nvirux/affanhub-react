<?php

use App\Models\PlatformSetting;
use App\Models\Store;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure platform setting fee cap is ₦1,000
        PlatformSetting::set(
            'paymint_max_fee_cap',
            1000.0,
            'float',
            'Maximum Gateway Fee Cap in NGN (Default: ₦1,000)'
        );

        // 2. Transition existing stores in production that were on 'free' or unconfigured (null)
        // to the platform rate: 1% capped at ₦1,000
        Store::all()->each(function (Store $store) {
            $currentType = $store->customer_funding_fee_type;
            if (empty($currentType) || $currentType === 'free') {
                $store->customer_funding_fee_type = 'percentage';
                $store->customer_funding_fee_amount = 1.0;
                $store->customer_funding_fee_cap = 1000.0;
                $store->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
