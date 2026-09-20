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
        Schema::create('store_mobile_apps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('app_name');
            $table->string('package_id')->unique();
            $table->string('app_icon_path')->nullable();
            $table->unsignedInteger('version_code')->default(1);
            $table->string('version_name')->default('1.0.0');
            $table->text('apk_download_url')->nullable();
            $table->string('status')->default('draft'); // draft, building, ready, failed
            $table->decimal('price_paid', 12, 2)->default(0.00);
            $table->string('github_run_id')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamp('last_built_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_mobile_apps');
    }
};
