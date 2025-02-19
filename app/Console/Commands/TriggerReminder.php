<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentSchedule;
use App\Notifications\PaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
class TriggerReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:trigger {schedule_id} {--test-email=} {--reminder-type=5_days_before}';

    public function handle()
    {
        $schedule = PaymentSchedule::findOrFail($this->argument('schedule_id'));
        $testEmail = $this->option('test-email');
        $reminderType = $this->option('reminder-type');

        $this->info("Sending reminder for schedule ID: {$schedule->id}");
        $this->info("Payment Type: {$schedule->payment_type}");
        $this->info("Amount: {$schedule->amount}");
        $this->info("Reminder type: {$reminderType}");

        try {
            $notification = new PaymentReminderNotification($schedule, $reminderType);
            
            if ($testEmail) {
                $this->info("Sending to test email: {$testEmail}");
                Notification::route('mail', $testEmail)
                    ->notify($notification);
            } else {
                $this->info("Sending to user email: {$schedule->user->email}");
                $schedule->user->notify($notification);
            }
            
            $this->info('Reminder sent successfully!');
            
            // Log success
            // Log::info('Reminder sent', [
            //     'schedule_id' => $schedule->id,
            //     'email' => $testEmail ?: $schedule->user->email,
            //     'reminder_type' => $reminderType
            // ]);

        } catch (\Exception $e) {
            $this->error('Failed to send reminder: ' . $e->getMessage());
            Log::error('Reminder failed', [
                'schedule_id' => $schedule->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
