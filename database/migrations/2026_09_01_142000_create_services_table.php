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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique(); // e.g., 'airtime', 'data', 'nin_verification'
            $table->string('category')->default('vtu'); // 'vtu' or 'identity'
            $table->string('icon')->nullable(); // Lucide icon identifier
            $table->string('description')->nullable();
            $table->foreignId('feature_id')->nullable()->constrained('features')->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true); // Platform global toggle
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
