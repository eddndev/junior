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
        Schema::create('notification_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('schedulable_type');
            $table->unsignedBigInteger('schedulable_id');
            $table->string('notification_type');
            $table->timestamp('scheduled_at');
            $table->timestamp('sent_at')->nullable();
            $table->boolean('is_cancelled')->default(false);
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index(['schedulable_type', 'schedulable_id'], 'schedules_schedulable_index');
            $table->index(['scheduled_at', 'sent_at'], 'schedules_pending_index');
            $table->index('notification_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_schedules');
    }
};
