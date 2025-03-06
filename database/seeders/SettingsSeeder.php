<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing settings to avoid duplicate key errors
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('settings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Define settings data from the SQL dump
        $settings = [
            [
                'id' => 1,
                'key' => 'enable_2fa',
                'name' => 'Two Factor Auth',
                'value' => '0',
                'type' => 'boolean',
                'created_at' => Carbon::parse('2024-12-11 03:20:00'),
                'updated_at' => Carbon::parse('2025-03-02 22:59:14'),
                'deleted_at' => null,
                'is_show' => 1,
            ],
            [
                'id' => 2,
                'key' => 'reminder_days_monthly',
                'name' => null,
                'value' => '{"7_days_before": 7, "5_days_before": 5, "2_days_before": 2}',
                'type' => 'string',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null,
                'is_show' => 0,
            ],
            [
                'id' => 3,
                'key' => 'reminder_days_bi-weekly',
                'name' => null,
                'value' => '{"5_days_before": 5, "3_days_before": 3, "2_days_before": 2}',
                'type' => 'string',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null,
                'is_show' => 0,
            ],
        ];
        
        // Insert settings data
        DB::table('settings')->insert($settings);
    }
}
