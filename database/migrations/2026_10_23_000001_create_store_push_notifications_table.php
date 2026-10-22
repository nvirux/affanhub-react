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
        if (! Schema::hasTable('store_push_notifications')) {
            Schema::create('store_push_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
                $table->string('title');
                $table->text('message');
                $table->string('action_url')->nullable();
                $table->string('target_audience')->default('all'); // all, active_buyers, etc.
                $table->string('status')->default('sent'); // sent, failed
                $table->unsignedInteger('recipients_count')->default(0);
                $table->string('onesignal_id')->nullable();
                $table->text('failure_reason')->nullable();
                $table->unsignedBigInteger('sent_by_id')->nullable();
                $table->timestamps();

                $table->index(['store_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_push_notifications');
    }
};
