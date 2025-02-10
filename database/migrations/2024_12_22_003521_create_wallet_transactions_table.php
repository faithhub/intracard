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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wallet_id');
            $table->uuid('uuid')->unique();
            $table->decimal('amount', 15, 2)->default(0.00); // Corrected for precision
            $table->decimal('charge', 10, 2)->default(0.00); // Corrected for precision
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->enum('type', ['inbound', 'outbound '])->default('inbound');
            $table->longText('details')->nullable(); // JSON or encrypted text for transaction details
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
