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
        Schema::create('bill_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('card_id');
            $table->uuid('uuid')->unique();
            $table->decimal('amount', 10, 2)->nullable(); // Payment amount
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('provider')->nullable();
            $table->date('due_date')->nullable();
            $table->string('account_number')->nullable();
            $table->string('frequency')->nullable();
            $table->string('car_model')->nullable();
            $table->string('car_year')->nullable();
            $table->string('car_vin')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
            // Foreign key constraints
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_histories');
    }
};
