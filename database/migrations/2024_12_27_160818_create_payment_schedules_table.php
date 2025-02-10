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
        Schema::create('payment_schedules', function (Blueprint $table) {
            $table->id(); // Unique identifier for the schedule
            $table->unsignedBigInteger('user_id'); // User associated with this schedule
            $table->string('payment_type'); // Type of payment: 'rent', 'mortgage', 'bill'
            $table->decimal('amount', 10, 2)->nullable(); // Payment amount
            $table->unsignedBigInteger('address_id')->nullable(); // Address ID for rent/mortgage
            $table->unsignedBigInteger('bill_history_id')->nullable(); // Bill history ID for bills (optional)
            $table->integer('recurring_day'); // Recurring day of the month (1–31)
            $table->date('duration_from'); // Start date for the schedule
            $table->date('duration_to'); // End date for the schedule
            $table->json('reminder_dates')->nullable(); // JSON to store reminder dates dynamically if needed
            $table->enum('status', ['paid', 'due', 'active', 'overdue'])->default('active'); // Status: 'active', 'paid', 'failed', etc.
            $table->timestamps(); // Created at and updated at timestamps
            $table->softDeletes(); // Soft delete column
        
            // Foreign key relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('bill_history_id')->references('id')->on('bill_histories')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_schedules');
    }
};
