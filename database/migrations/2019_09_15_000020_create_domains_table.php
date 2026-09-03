<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain', 255)->unique();
            $table->string('tenant_id');
            $table->boolean('is_primary')->default(false);
            $table->string('status')->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('verification_status')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_routing_enabled')->default(false);
            $table->string('routing_disabled_reason')->nullable();
            $table->string('verification_error')->nullable();
            $table->string('verification_token')->nullable();
            $table->integer('last_http_status')->nullable();
            $table->string('last_resolved_ip')->nullable();
            $table->text('last_verification_message')->nullable();
            $table->boolean('cloudflare_detected')->default(false);
            $table->timestamp('origin_verified_at')->nullable();
            $table->timestamp('ownership_verified_at')->nullable();
            $table->timestamp('connection_verified_at')->nullable();
            $table->boolean('is_legacy')->default(false);
            $table->timestamp('migrated_at')->nullable();
            $table->text('verification_debug_info')->nullable();
            $table->integer('verification_attempts')->default(0);
            $table->timestamp('last_verification_attempt_at')->nullable();
            $table->timestamp('verification_failed_at')->nullable();
            $table->timestamp('verification_paused_at')->nullable();
            $table->string('verification_failure_reason')->nullable();

            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
}
