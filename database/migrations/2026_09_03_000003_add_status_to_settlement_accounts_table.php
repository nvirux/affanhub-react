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
        Schema::table('settlement_accounts', function (Blueprint $table) {
            if (! Schema::hasColumn('settlement_accounts', 'status')) {
                $table->string('status')->default('pending')->after('is_active'); // pending, approved, rejected
            }
            if (! Schema::hasColumn('settlement_accounts', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settlement_accounts', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_notes']);
        });
    }
};
