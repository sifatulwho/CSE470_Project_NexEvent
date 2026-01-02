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

            // Event reference
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();

            // Attendee reference
            $table->foreignId('attendee_id')->constrained('users')->cascadeOnDelete();

            // Checked-in by (nullable, SET NULL on delete)
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();

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
        Schema::table('event_checkins', function (Blueprint $table) {
            // Drop foreign keys first to avoid MySQL errors
            $table->dropForeign(['event_id']);
            $table->dropForeign(['attendee_id']);
            $table->dropForeign(['checked_in_by']);
        });

        Schema::dropIfExists('event_checkins');
    }
};
