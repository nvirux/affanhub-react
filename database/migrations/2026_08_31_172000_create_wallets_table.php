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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('holder_type');
            $table->unsignedBigInteger('holder_id');
            $table->string('type')->default('main'); // e.g. main, profit
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->string('currency', 3)->default('NGN');
            $table->string('status')->default('active'); // active, locked
            $table->timestamps();

            $table->index(['holder_type', 'holder_id']);
            $table->unique(['holder_type', 'holder_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
