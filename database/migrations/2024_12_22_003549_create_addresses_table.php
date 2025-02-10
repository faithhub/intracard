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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // UUID for unique identification
            $table->unsignedBigInteger('user_id'); // Foreign key to the users table
            $table->longText('name'); // Address name
            $table->decimal('amount', 10, 2); // Amount with precise decimal
            $table->longText('province')->nullable(); // Optional province
            $table->longText('city')->nullable(); // Optional city
            $table->longText('street_name')->nullable(); // Optional street name
            $table->longText('postal_code')->nullable(); // Optional postal code
            $table->longText('house_number')->nullable(); // Optional house number
            $table->longText('unit_number')->nullable(); // Optional unit number
            $table->integer('reoccurring_monthly_day')->nullable(); // Day of the month for recurring payments
            $table->date('duration_from')->nullable(); // Start date
            $table->date('duration_to')->nullable(); // End date
            $table->longText('tenancyAgreement')->nullable(); // End date
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
        Schema::dropIfExists('addresses');
    }
};
