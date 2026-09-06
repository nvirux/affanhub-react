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
        Schema::create('store_feature_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained('features')->cascadeOnDelete();
            $table->string('value'); // override value stored as string
            $table->text('reason')->nullable(); // why was this override granted
            $table->foreignId('granted_by')->nullable()->constrained('admins')->nullOnDelete(); // admin id who granted it
            $table->timestamps();

            $table->unique(['store_id', 'feature_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_feature_overrides');
    }
};
