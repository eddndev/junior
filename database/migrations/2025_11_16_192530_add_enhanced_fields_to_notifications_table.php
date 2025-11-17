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
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('notification_type')->nullable()->after('type')->index();
            $table->string('notifiable_type')->nullable()->after('notification_type');
            $table->unsignedBigInteger('notifiable_id')->nullable()->after('notifiable_type');
            $table->string('action_url')->nullable()->after('notifiable_id');
            $table->string('action_text')->nullable()->after('action_url');
            $table->json('data')->nullable()->after('action_text');
            $table->string('icon')->nullable()->after('data');
            $table->string('icon_color')->nullable()->after('icon');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('icon_color');
            $table->string('group')->nullable()->after('priority')->index();

            $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_notifiable_index');
            $table->dropIndex(['notification_type']);
            $table->dropIndex(['group']);
            $table->dropColumn([
                'notification_type',
                'notifiable_type',
                'notifiable_id',
                'action_url',
                'action_text',
                'data',
                'icon',
                'icon_color',
                'priority',
                'group',
            ]);
        });
    }
};
