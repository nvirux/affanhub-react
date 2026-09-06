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
        Schema::create('store_data_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('data_plan_id')->constrained('data_plans')->cascadeOnDelete();
            $table->decimal('selling_price', 10, 2);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->unique(['store_id', 'data_plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_data_plans');
    }
};
