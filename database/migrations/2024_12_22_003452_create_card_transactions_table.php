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
        Schema::create('card_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('card_id');
            $table->uuid('uuid')->unique();
            $table->decimal('amount', 15, 2)->default(0.00); // Corrected for precision
            $table->decimal('charge', 10, 2)->default(0.00); // Corrected for precision
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->enum('type', ['credit', 'debit'])->default('debit');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_transactions');
    }
};
