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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->foreignId('event_resource_id')->nullable()->constrained('event_resources')->onDelete('cascade');
            $table->timestamps();

            // Ensure a user can only wishlist an event once
            $table->unique(['user_id', 'event_id'], 'wishlist_user_event_unique');
            
            // Ensure a user can only wishlist a resource once
            $table->unique(['user_id', 'event_resource_id'], 'wishlist_user_resource_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};

