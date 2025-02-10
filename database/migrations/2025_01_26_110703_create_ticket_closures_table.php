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
        Schema::create('ticket_closures', function (Blueprint $table) {
            $table->uuid('id')->primary();  // Change to UUID primary
            $table->foreignId('ticket_id')->constrained();
            $table->foreignId('closed_by')->constrained('users');
            $table->enum('resolution_status', ['resolved', 'unresolved']);
            $table->text('reason')->nullable();
            $table->text('feedback')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_closures');
    }
};
