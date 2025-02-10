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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'deactivated'])->default('pending');
            $table->enum('role', ['member', 'admin'])->default('member');
            $table->decimal('percentage', 10, 2)->nullable(); // Corrected precision for monetary values
            $table->decimal('amount', 10, 2)->nullable(); // Corrected precision for monetary values
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
