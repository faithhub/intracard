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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->longText('token'); // Encrypted token field
            $table->uuid('uuid')->unique();
            $table->string('card_number'); // Masked card number field
            $table->enum('status', ['active', 'inactive', 'removed'])->default('active');
            $table->string('name_on_card');
            $table->string('type');
            $table->integer('expiry_month');
            $table->integer('expiry_year');
            $table->string('cvv'); // Encrypted CVV field
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
