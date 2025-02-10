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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('schedule_id')->constrained('payment_schedules');
            $table->foreignId('team_id')->nullable()->constrained();
            $table->decimal('amount', 10, 2);
            $table->decimal('charges', 10, 2);
            $table->string('payment_for');
            $table->foreignId('bill_id')->constrained();
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->foreignId('card_id')->nullable()->constrained();
            $table->string('transaction_reference')->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->boolean('is_team_payment')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
