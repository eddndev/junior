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
        Schema::create('calendar_event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_event_id')->constrained('calendar_events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_required')->default(true);
            $table->enum('attendance_status', ['pending', 'confirmed', 'declined', 'tentative'])->default('pending');
            $table->enum('actual_attendance', ['present', 'absent', 'late', 'excused'])->nullable();
            $table->time('arrival_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint: one user per event
            $table->unique(['calendar_event_id', 'user_id'], 'unique_participant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_event_participants');
    }
};
