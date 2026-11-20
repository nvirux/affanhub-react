<?php

use App\Models\Plan;
use App\Models\Service;
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
        Schema::create('plan_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['plan_id', 'service_id']);
        });

        // Seed initial plan_service mappings based on existing plans and services
        $starter = Plan::where('slug', 'starter')->first();
        $pro = Plan::where('slug', 'pro')->first();
        $enterprise = Plan::where('slug', 'enterprise')->first();

        if (! $starter && ! $pro && ! $enterprise) {
            return;
        }

        $allServices = Service::all();

        foreach ($allServices as $service) {
            // Enterprise gets all services
            if ($enterprise) {
                DB::table('plan_service')->insertOrIgnore([
                    'plan_id' => $enterprise->id,
                    'service_id' => $service->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Pro gets all VTU, Identity verifications, and CAC
            if ($pro) {
                DB::table('plan_service')->insertOrIgnore([
                    'plan_id' => $pro->id,
                    'service_id' => $service->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Starter gets standard VTU services (airtime, data, cable, electricity, education, bulk_sms) and CAC if configured
            if ($starter) {
                $isStarterAllowed = in_array($service->key, [
                    'airtime',
                    'data',
                    'cable',
                    'electricity',
                    'education',
                    'bulk_sms',
                    'cac_services',
                ], true);

                if ($isStarterAllowed) {
                    DB::table('plan_service')->insertOrIgnore([
                        'plan_id' => $starter->id,
                        'service_id' => $service->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_service');
    }
};
