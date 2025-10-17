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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('content');
            $table->enum('type', ['general', 'area', 'announcement'])->default('general')->index();
            $table->timestamps();

            // Índices para consultas
            $table->index('sender_id');
            $table->index('area_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
