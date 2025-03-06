<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
{
    // Truncate the admins table and role_admins (pivot) table to remove existing data
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    DB::table('admins')->truncate();
    DB::table('role_admins')->truncate(); // Also clear the pivot table
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    $admins = [
        [
            'first_name' => 'Admin',
            'last_name' => 'One',
            'middle_name' => 'A.',
            'phone' => '1234567890',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('Oluwadara+1'), // Encrypted password
            'otp_code' => Str::random(6), // Random OTP
            'otp_expires_at' => now()->addMinutes(10), // OTP expires in 10 minutes
            'otp_verified' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'roles' => ['system_admin'], // Role slug(s) to assign
        ],
        // You can add more admins here as needed
    ];
    
    foreach ($admins as $adminData) {
        // Extract roles before creating admin
        $roles = $adminData['roles'] ?? [];
        unset($adminData['roles']);
        
        // Create the admin
        $admin = Admin::create($adminData);
        
        // Assign roles by slug
        if (!empty($roles)) {
            $roleIds = Role::whereIn('slug', $roles)->pluck('id')->toArray();
            $admin->roles()->attach($roleIds);
        }
    }
    
    $this->command->info('Admin users seeded with roles!');
}
}
