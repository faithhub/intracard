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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable()->after('id'); // Add team_id column
            $table->boolean('is_team')->default(false)->after('team_id'); // Add is_team column

            // Add foreign key constraint
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['team_id']); // Drop foreign key constraint
            $table->dropColumn(['team_id', 'is_team']); // Drop columns
        });
    }
};
