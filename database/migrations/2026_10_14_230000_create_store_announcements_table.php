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
        if (! Schema::hasTable('store_announcements')) {
            Schema::create('store_announcements', function (Blueprint $table) {
                $table->id();
                $table->string('store_id');
                $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
                $table->string('title');
                $table->text('message');
                $table->string('type')->default('info'); // info, warning, danger, promo
                $table->string('display_style')->default('modal'); // modal, banner, ticker
                $table->string('action_label')->nullable();
                $table->string('action_url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_dismissible')->default(true);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->index(['store_id', 'is_active']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_announcements');
    }
};
