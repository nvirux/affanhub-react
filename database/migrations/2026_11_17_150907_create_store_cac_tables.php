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
        Schema::create('store_cac_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(25000.00);
            $table->string('processing_time')->default('3 - 5 Days');
            $table->json('required_documents')->nullable();
            $table->json('fields')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['store_id', 'is_active']);
            $table->unique(['store_id', 'slug']);
        });

        Schema::create('cac_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 32)->unique();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('store_cac_service_id')->constrained('store_cac_services')->cascadeOnDelete();
            $table->string('service_name');
            $table->string('business_name')->nullable();
            $table->json('submitted_fields')->nullable();
            $table->json('uploaded_documents')->nullable();
            $table->decimal('amount_paid', 10, 2);
            $table->string('status', 20)->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('result_file_path')->nullable();
            $table->string('settlement_type', 30)->default('online_profit_credit');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cac_requests');
        Schema::dropIfExists('store_cac_services');
    }
};
