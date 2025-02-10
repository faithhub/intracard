<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('payments:process')
->dailyAt('00:00') // Runs daily at 12:00 AM
->timezone('America/Toronto'); // Canada Eastern Time
// Available Canadian Timezones
// Depending on your specific region in Canada, use the appropriate timezone string:

// Region	Timezone
// Eastern Time (ET)	America/Toronto
// Central Time (CT)	America/Winnipeg
// Mountain Time (MT)	America/Edmonton
// Pacific Time (PT)	America/Vancouver

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
