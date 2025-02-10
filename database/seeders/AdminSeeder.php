<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admins = [
            [
                'first_name' => 'Admin',
                'last_name' => 'One',
                'middle_name' => 'A.',
                'phone' => '1234567890',
                'email' =>'admin1@gmail.com',
                'password' => Hash::make('Oluwadara+1'), // Encrypted password
                'otp_code' => Str::random(6), // Random OTP
                'otp_expires_at' => now()->addMinutes(10), // OTP expires in 10 minutes
                'otp_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'Two',
                'middle_name' => 'B.',
                'phone' => '0987654321',
                'email' =>'admin2@gmail.com',
                'password' => Hash::make('Oluwadara+1'), // Encrypted password
                'otp_code' => Str::random(6), // Random OTP
                'otp_expires_at' => now()->addMinutes(10), // OTP expires in 10 minutes
                'otp_verified' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
