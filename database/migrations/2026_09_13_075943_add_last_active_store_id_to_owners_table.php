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
        if (! Schema::hasColumn('owners', 'last_active_store_id')) {
            Schema::table('owners', function (Blueprint $table) {
                $table->unsignedBigInteger('last_active_store_id')->nullable()->after('max_stores')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('owners', 'last_active_store_id')) {
            Schema::table('owners', function (Blueprint $table) {
                $table->dropColumn('last_active_store_id');
            });
        }
    }
};
