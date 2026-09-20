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
        Schema::table('store_mobile_apps', function (Blueprint $table) {
            $table->boolean('include_playstore')->default(false)->after('price_paid');
            $table->string('playstore_status')->default('not_requested')->after('include_playstore'); // not_requested, pending_submission, submitted, published, rejected
            $table->decimal('playstore_paid', 12, 2)->default(0.00)->after('playstore_status');
            $table->text('playstore_url')->nullable()->after('playstore_paid');
            $table->text('aab_download_url')->nullable()->after('apk_download_url');
            $table->string('custom_keystore_path')->nullable()->after('failure_reason');
            $table->string('custom_keystore_alias')->nullable()->after('custom_keystore_path');
            $table->string('custom_keystore_password')->nullable()->after('custom_keystore_alias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_mobile_apps', function (Blueprint $table) {
            $table->dropColumn([
                'include_playstore',
                'playstore_status',
                'playstore_paid',
                'playstore_url',
                'aab_download_url',
                'custom_keystore_path',
                'custom_keystore_alias',
                'custom_keystore_password',
            ]);
        });
    }
};
