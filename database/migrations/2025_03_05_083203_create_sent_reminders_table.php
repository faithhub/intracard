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
        // Schema::create('sent_reminders', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('sent_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('schedule_id')->constrained('payment_schedules');
            $table->string('reminder_type');
            $table->date('reminder_date');
            $table->date('payment_date');
            $table->string('period_key');
            $table->timestamps();
            
            // Create a unique constraint with a custom shorter name
            $table->unique(
                ['user_id', 'schedule_id', 'reminder_type', 'payment_date'],
                'unique_sent_reminder' // Much shorter custom name
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_reminders');
    }
};
