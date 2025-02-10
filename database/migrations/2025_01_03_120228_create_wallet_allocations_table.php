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
        Schema::create('wallet_allocations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('user_id'); // Directly references the user
            $table->unsignedBigInteger('wallet_id'); // References the wallet
            $table->unsignedBigInteger('bill_id');  // References the bill (purpose)
            $table->decimal('allocated_amount', 15, 2); // Total allocated amount
            $table->decimal('spent_amount', 15, 2)->default(0.00); // Total spent amount
            $table->decimal('remaining_amount', 15, 2); // Remaining amount
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_allocations');
    }
};
