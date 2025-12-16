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
        Schema::create('event_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('attendee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('checked_in_by')->constrained('users')->onDelete('set null')->nullable();
            $table->timestamp('checked_in_at');
            $table->string('check_in_method')->default('manual'); // manual, qr_code, etc
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure unique check-in per event per user
            $table->unique(['event_id', 'attendee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_checkins');
    }
};
