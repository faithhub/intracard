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
        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('team_id')->nullable()->constrained();
            $table->date('payment_date');
            $table->date('reminder_date');
            $table->string('reminder_type'); // e.g., '7_days_before'
            $table->string('status')->default('pending'); // pending, sent, cancelled
            $table->string('payment_status')->default('pending'); // pending, completed, failed
            $table->decimal('amount', 10, 2)->nullable(); // The amount to be paid
            $table->decimal('charges', 10, 2)->nullable(); // Any processing fees or service charges
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->string('period_key')->nullable(); // For tracking specific payment periods
            $table->boolean('is_team_reminder')->default(false);
            $table->foreignId('payment_id')->nullable()->constrained(); // Reference to the payment record if completed
            $table->timestamps();
            
            // Use a shorter name for the unique constraint
            $table->unique(
                ['user_id', 'payment_schedule_id', 'payment_date', 'reminder_type'],
                'unique_payment_reminder'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_reminders');
    }
};
