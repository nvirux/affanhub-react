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
        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('wallet_transactions', 'category')) {
                $table->string('category')->nullable()->after('type');
            }
            if (! Schema::hasColumn('wallet_transactions', 'balance_before')) {
                $table->decimal('balance_before', 15, 2)->default(0.00)->after('amount');
            }
            if (! Schema::hasColumn('wallet_transactions', 'balance_after')) {
                $table->decimal('balance_after', 15, 2)->default(0.00)->after('balance_before');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn(['category', 'balance_before', 'balance_after']);
        });
    }
};
