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
        Schema::table('store_modification_services', function (Blueprint $table) {
            $table->json('fields')->nullable()->after('required_documents');
        });

        Schema::table('modification_requests', function (Blueprint $table) {
            $table->json('submitted_fields')->nullable()->after('new_data');
        });
    }

    public function down(): void
    {
        Schema::table('modification_requests', function (Blueprint $table) {
            $table->dropColumn('submitted_fields');
        });

        Schema::table('store_modification_services', function (Blueprint $table) {
            $table->dropColumn('fields');
        });
    }
};
