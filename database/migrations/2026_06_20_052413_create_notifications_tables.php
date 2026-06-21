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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('target_roles');
            $table->string('message');
            $table->string('priority')->default('medium'); // 'low', 'medium', 'high'
            $table->boolean('is_urgent')->default(false);
            $table->string('action_url')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_read_statuses', function (Blueprint $table) {
            $table->id();
            $table->uuid('notification_id');
            $table->foreign('notification_id')->references('id')->on('notifications')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->unique(['notification_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_read_statuses');
        Schema::dropIfExists('notifications');
    }
};

