<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bills = [
            [
                'value' => 'carBill',
                'uuid' => (string) Str::uuid(),
                'name' => 'Car Bill',
            ],
            [
                'value' => 'phoneBill',
                'uuid' => (string) Str::uuid(),
                'name' => 'Phone Bill',
            ],
            [
                'value' => 'internetBill',
                'uuid' => (string) Str::uuid(),
                'name' => 'Internet Bill',
            ],
            [
                'value' => 'utilityBill',
                'uuid' => (string) Str::uuid(),
                'name' => 'Utility Bill',
            ],
            [
                'value' => 'rent',
                'uuid' => (string) Str::uuid(),
                'name' => 'Rent',
            ],
            [
                'value' => 'utilityBill',
                'uuid' => (string) Str::uuid(),
                'name' => 'Mortgage',
            ],
        ];

        DB::table('bills')->insert($bills);
    }
}
