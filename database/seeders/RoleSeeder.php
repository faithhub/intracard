<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing roles
        DB::table('roles')->truncate();
        
        $roles = [
            ['name' => 'System Admin', 'slug' => 'system_admin', 'description' => 'Full access to the entire admin panel, including system-wide settings and configuration.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Manage users, onboarding, support access, and reports. Cannot manage system-wide settings.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'User Manager', 'slug' => 'user_manager', 'description' => 'Handle user profiles, user onboarding, approval/rejection, and user data management.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Support', 'slug' => 'support', 'description' => 'Manage customer support tickets, help center, and escalate issues if necessary.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finance', 'slug' => 'finance', 'description' => 'Manage financial transactions, refunds, disputes, and financial reports.', 'created_at' => now(), 'updated_at' => now()],
        ];
    
        DB::table('roles')->insert($roles);
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
