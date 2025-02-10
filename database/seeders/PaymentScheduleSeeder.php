<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentSchedule;
use Carbon\Carbon;

class PaymentScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentSchedule::create([
            'user_id' => 2,
            'payment_type' => 'rent',
            'payment_date' => Carbon::now()->addDays(10),
            'reminder_dates' => json_encode([
                '6_days_before' => Carbon::now()->addDays(4)->toDateString(),
                '3_days_before' => Carbon::now()->addDays(7)->toDateString(),
                '1_day_before' => Carbon::now()->addDays(9)->toDateString(),
            ]),
            'status' => 'due',
        ]);
        
        PaymentSchedule::create([
            'user_id' => 2,
            'payment_type' => 'mortgage',
            'payment_date' => Carbon::now()->addDays(15),
            'reminder_dates' => json_encode([
                '6_days_before' => Carbon::now()->addDays(9)->toDateString(),
                '3_days_before' => Carbon::now()->addDays(12)->toDateString(),
                '1_day_before' => Carbon::now()->addDays(14)->toDateString(),
            ]),
            'status' => 'due',
        ]);
        
        PaymentSchedule::create([
            'user_id' => 2,
            'payment_type' => 'bill',
            'payment_date' => Carbon::now()->addDays(7),
            'reminder_dates' => json_encode([
                '6_days_before' => Carbon::now()->addDay()->toDateString(),
                '3_days_before' => Carbon::now()->addDays(4)->toDateString(),
                '1_day_before' => Carbon::now()->addDays(6)->toDateString(),
            ]),
            'status' => 'due',
        ]);
    }
}
