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
        Schema::create('airtime_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('network_id')->unique()->constrained('networks')->cascadeOnDelete();
            $table->decimal('buy_discount', 5, 2)->default(3.50);
            $table->decimal('default_merchant_discount', 5, 2)->default(2.00);
            $table->decimal('default_retail_discount', 5, 2)->default(1.50);
            $table->decimal('min_amount', 10, 2)->default(50.00);
            $table->decimal('max_amount', 10, 2)->default(50000.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airtime_discounts');
    }
};
