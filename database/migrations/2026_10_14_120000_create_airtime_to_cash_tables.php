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
        Schema::create('store_airtime_cash_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('network_id')->constrained('networks')->cascadeOnDelete();
            $table->string('receiving_phone')->nullable();
            $table->decimal('exchange_rate', 5, 2)->default(80.00); // 80.00%
            $table->decimal('min_amount', 10, 2)->default(500.00);
            $table->decimal('max_amount', 10, 2)->default(50000.00);
            $table->string('ussd_code_template')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->unique(['store_id', 'network_id']);
        });

        Schema::create('airtime_cash_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique()->index();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('network_id')->constrained('networks')->cascadeOnDelete();
            $table->decimal('airtime_amount', 10, 2);
            $table->decimal('exchange_rate', 5, 2);
            $table->decimal('cash_amount', 10, 2);
            $table->string('sender_phone');
            $table->string('receiving_phone');
            $table->string('payout_method')->default('wallet'); // 'wallet' or 'bank'
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airtime_cash_requests');
        Schema::dropIfExists('store_airtime_cash_settings');
    }
};
