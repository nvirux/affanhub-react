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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->timestamp('grace_ends_at')->nullable()->after('ends_at');
            $table->timestamp('reminder_3days_sent_at')->nullable()->after('grace_ends_at');
            $table->timestamp('reminder_1day_sent_at')->nullable()->after('reminder_3days_sent_at');
            $table->timestamp('grace_notified_at')->nullable()->after('reminder_1day_sent_at');
            $table->timestamp('downgraded_at')->nullable()->after('grace_notified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'grace_ends_at',
                'reminder_3days_sent_at',
                'reminder_1day_sent_at',
                'grace_notified_at',
                'downgraded_at',
            ]);
        });
    }
};
