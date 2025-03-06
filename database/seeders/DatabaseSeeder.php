<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,  // Run roles seeder first
            AdminSeeder::class, // Then run admins seeder
            SettingsSeeder::class,
        ]);

        // $this->call(BillSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(AdminSeeder::class);
        // $this->call(SettingsSeeder::class);
        // $this->call(RoleSeeder::class);
        // $this->call(NotificationSeeder::class);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
