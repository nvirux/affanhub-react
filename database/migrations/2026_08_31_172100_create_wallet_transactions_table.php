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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // credit, debit
            $table->string('category')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2)->default(0.00);
            $table->decimal('balance_after', 15, 2)->default(0.00);
            $table->string('reference')->unique();
            $table->string('description');
            $table->string('status')->default('pending'); // pending, success, failed
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
