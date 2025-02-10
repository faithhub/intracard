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
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id')->nullable(); // Foreign key for regular users
            $table->unsignedBigInteger('admin_id')->nullable(); // Foreign key for admins
            $table->text('title'); // Store encrypted title
            $table->text('message'); // Store encrypted message
            $table->enum('category', ['general', 'payment', 'reminder'])->default('general'); // Category as ENUM
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal'); // Priority level
            $table->boolean('is_read')->default(false); // Read status
            $table->boolean('is_archived')->default(false); // Archived status
            $table->timestamp('expires_at')->nullable(); // Expiry timestamp for notifications
            $table->timestamps(); // Created and updated timestamps

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
