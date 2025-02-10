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
        Schema::create('landlord_financer_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // UUID for unique identification
            $table->unsignedBigInteger('address_id'); // Foreign key to the address table
            $table->enum('type', ['rent', 'mortgage'])->default('rent'); // Type of entity
            $table->enum('payment_method', ['EFT', 'cheque', 'interac'])->default('cheque'); // Type of entity
            $table->enum('landlordType', ['business', 'individual'])->nullable(); // Type of entity
            $table->longText('details'); // Encrypted details field
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landlord_financer_details');
    }
};
