<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreAndDomainSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a demo Owner (Merchant)
        $owner = Owner::updateOrCreate(
            ['email' => 'merchant@affanhub.com'],
            [
                'name' => 'Demo Merchant',
                'phone' => '08012345678',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create the demo Store
        $store = Store::firstOrCreate(
            ['name' => 'Demo Storefront'],
            [
                'public_id' => 'str_demo',
                'owner_id' => $owner->id,
                'status' => 'active',
            ]
        );

        // 3. Create the Domain mapping for this store in Stancl Tenancy
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
        $domains = array_unique(array_filter([
            'demo.localhost',
            'demo.idcore.africa',
            $baseDomain !== 'localhost' ? 'demo.'.$baseDomain : null,
        ]));

        foreach ($domains as $domainName) {
            DB::table('domains')->updateOrInsert(
                ['domain' => $domainName],
                [
                    'tenant_id' => $store->id,
                    'is_primary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 4. Associate the Owner with the 'owner' role in the pivot table
        DB::table('store_owner')->updateOrInsert(
            ['store_id' => $store->id, 'owner_id' => $owner->id],
            [
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. Subscribe the Store to the Pro Plan automatically so it has feature access
        $proPlan = Plan::where('slug', 'pro')->first();
        if ($proPlan) {
            $store->subscriptions()->updateOrCreate(
                ['plan_id' => $proPlan->id, 'status' => 'active'],
                [
                    'price' => $proPlan->price_monthly,
                    'billing_interval' => 'month',
                    'starts_at' => now(),
                    'ends_at' => now()->addMonth(),
                ]
            );
        }

        // 6. Create a demo customer user inside this store
        User::updateOrCreate(
            ['email' => 'customer@demo.com'],
            [
                'store_id' => $store->id,
                'name' => 'Demo Customer',
                'phone' => '08012345680',
                'password' => Hash::make('password'),
            ]
        );
    }
}
