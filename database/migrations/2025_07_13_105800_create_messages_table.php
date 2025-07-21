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
        // Check if users table exists before creating messages table
        if (!Schema::hasTable('users')) {
            throw new Exception('Users table does not exist. Please run users migration first.');
        }

        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Participants - use the same data type as users.id
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');

            // Body & status
            $table->text('message');
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            // FK constraints with proper error handling
            $table->foreign('sender_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('receiver_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // Indexes to speed up look‑ups
            $table->index(['sender_id', 'receiver_id']);
            $table->index(['receiver_id', 'is_read']);
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
