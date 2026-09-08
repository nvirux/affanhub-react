<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('data_plans') && ! Schema::hasColumn('data_plans', 'is_best_offer')) {
            Schema::table('data_plans', function (Blueprint $table) {
                $table->boolean('is_best_offer')->default(false)->after('is_active');
            });
        }

        if (Schema::hasTable('store_data_plans') && ! Schema::hasColumn('store_data_plans', 'is_best_offer')) {
            Schema::table('store_data_plans', function (Blueprint $table) {
                $table->boolean('is_best_offer')->nullable()->after('is_enabled');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('data_plans') && Schema::hasColumn('data_plans', 'is_best_offer')) {
            Schema::table('data_plans', function (Blueprint $table) {
                $table->dropColumn('is_best_offer');
            });
        }

        if (Schema::hasTable('store_data_plans') && Schema::hasColumn('store_data_plans', 'is_best_offer')) {
            Schema::table('store_data_plans', function (Blueprint $table) {
                $table->dropColumn('is_best_offer');
            });
        }
    }
};
