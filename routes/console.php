<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\PaymentSchedule;
use App\Models\User;
use Carbon\Carbon;

Schedule::command('payments:process')
->dailyAt('00:00') // Runs daily at 12:00 AM
->timezone('America/Toronto')
->withoutOverlapping()
->onOneServer(); // Canada Eastern Time

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

// Artisan::command('reminders:send {schedule_id?} {reminder_type=7_days_before}', function ($scheduleId = null, $reminderType = '7_days_before') {
//     $this->info("Starting manual reminder process");
    
//     if ($scheduleId) {
//         $schedules = PaymentSchedule::where('id', $scheduleId)->get();
//     } else {
//         $schedules = PaymentSchedule::where('status', 'active')->get();
//     }
    
//     $this->info("Processing {$schedules->count()} payment schedules");
    
//     foreach ($schedules as $schedule) {
//         $this->info("\nProcessing Schedule ID: {$schedule->id}");
        
//         // Get user
//         $user = User::find($schedule->user_id);
//         if (!$user) {
//             $this->error("User not found for schedule {$schedule->id}");
//             continue;
//         }
        
//         // Process individual reminder
//         try {
//             $this->info("Sending individual reminder to {$user->email}");
//             // Directly notify instead of using job to bypass queue
//             $user->notify(new \App\Notifications\PaymentReminderNotification(
//                 $schedule,
//                 $reminderType
//             ));
//             $this->info("Individual reminder sent successfully");
//         } catch (\Exception $e) {
//             $this->error("Failed to send individual reminder: " . $e->getMessage());
//             Log::error("Failed to send individual reminder", [
//                 'schedule_id' => $schedule->id,
//                 'user_id' => $user->id,
//                 'error' => $e->getMessage()
//             ]);
//         }
        
//         // Process team reminders if applicable
//         if ($schedule->team_id) {
//             $team = $schedule->team;
//             if (!$team) {
//                 $this->warn("Team not found for schedule {$schedule->id}");
//                 continue;
//             }
            
//             $teamMembers = $team->members;
//             $this->info("Found {$teamMembers->count()} team members");
            
//             foreach ($teamMembers as $member) {
//                 try {
//                     $this->info("Sending team reminder to {$member->email}");
//                     // Directly notify instead of using job to bypass queue
//                     $member->notify(new \App\Notifications\TeamMemberPaymentReminderNotification(
//                         $schedule,
//                         $reminderType
//                     ));
//                     $this->info("Team reminder sent successfully to {$member->email}");
//                 } catch (\Exception $e) {
//                     $this->error("Failed to send team reminder to {$member->email}: " . $e->getMessage());
//                     Log::error("Failed to send team reminder", [
//                         'schedule_id' => $schedule->id,
//                         'member_id' => $member->id,
//                         'error' => $e->getMessage()
//                     ]);
//                 }
//             }
//         }
//     }
    
//     $this->info("\nReminder processing completed");
// })->purpose('Manually send payment reminders');