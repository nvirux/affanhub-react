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
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('slip_id')->nullable()->constrained('slips')->nullOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();

            $table->string('search_type');
            $table->string('search_value');
            $table->string('reference')->unique();
            $table->string('status')->default('successful');

            $table->string('recipient_name')->nullable();
            $table->string('tracking_id')->nullable();
            $table->text('photo_url')->nullable();
            $table->text('slip_download_url')->nullable();
            $table->string('slip_file_path')->nullable();

            $table->json('data_payload')->nullable();
            $table->decimal('fee_charged', 10, 2);
            $table->decimal('cost_price', 10, 2)->default(0.00);
            $table->decimal('merchant_cost', 10, 2)->default(0.00);
            $table->decimal('profit', 10, 2)->default(0.00);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_verifications');
    }
};
