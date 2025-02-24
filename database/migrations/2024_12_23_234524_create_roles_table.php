<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();             // Human-readable role name (e.g., "System Admin")
            $table->string('slug')->unique();             // Machine-readable identifier (e.g., "system_admin")
            $table->text('description')->nullable();     // Role description (e.g., responsibilities, access level)
            $table->timestamps();
        });

        // Insert predefined roles with descriptions
        DB::table('roles')->insert([
            ['name' => 'System Admin', 'slug' => 'system_admin', 'description' => 'Full access to the entire admin panel, including system-wide settings and configuration.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Manage users, onboarding, support access, and reports. Cannot manage system-wide settings.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'User Manager', 'slug' => 'user_manager', 'description' => 'Handle user profiles, user onboarding, approval/rejection, and user data management.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Support', 'slug' => 'support', 'description' => 'Manage customer support tickets, help center, and escalate issues if necessary.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finance', 'slug' => 'finance', 'description' => 'Manage financial transactions, refunds, disputes, and financial reports.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
