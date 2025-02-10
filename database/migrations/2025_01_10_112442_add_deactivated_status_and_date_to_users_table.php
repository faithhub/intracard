<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the new enum value for the status column
            DB::statement("ALTER TABLE users MODIFY status ENUM('active','pending','inactive','suspended','deleted', 'deactivated') NOT NULL DEFAULT 'active'");

            // Add a new column for the date of deactivation
            $table->timestamp('date_deactivated')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert the enum to its original values
            DB::statement("ALTER TABLE users MODIFY status ENUM('active','pending','inactive','suspended','deleted') NOT NULL DEFAULT 'active'");

            // Drop the date_deactivated column
            $table->dropColumn('date_deactivated');
        });
    }
};
