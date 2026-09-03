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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->string('service_type'); // airtime, data, tv, electricity
            $table->decimal('amount', 15, 2);
            $table->decimal('cost_price', 15, 2);
            $table->decimal('profit', 15, 2);
            $table->string('recipient'); // e.g. phone number or meter number
            $table->string('status')->default('pending'); // pending, success, failed
            $table->json('api_response')->nullable();
            $table->string('reference')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
