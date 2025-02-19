<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\PaymentSchedule;

class VerifyReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:verify {schedule_id?}';
    protected $description = 'Verify reminder dates for payment schedules';

    public function handle()
    {
        $scheduleId = $this->argument('schedule_id');

        if ($scheduleId) {
            // Verify single schedule
            $schedule = PaymentSchedule::find($scheduleId);
            if (!$schedule) {
                $this->error("Schedule ID {$scheduleId} not found");
                return;
            }
            $this->verifySchedule($schedule);
        } else {
            // Verify all active schedules
            $schedules = PaymentSchedule::where('status', 'active')->get();
            $this->info("Verifying {$schedules->count()} active schedules...\n");
            
            foreach ($schedules as $schedule) {
                $this->verifySchedule($schedule);
            }
        }
    }

    private function verifySchedule($schedule)
    {
        $this->info("Checking Schedule ID: {$schedule->id}");
        $this->info("Payment Type: {$schedule->payment_type}");
        $this->info("Amount: {$schedule->amount}");

        $issues = $schedule->verifyReminders();
        
        if (empty($issues)) {
            $this->info("✅ All reminders are valid!\n");
        } else {
            $this->error("❌ Issues found:");
            foreach ($issues as $issue) {
                $this->warn("  - {$issue}");
            }
            $this->line("");
        }
    }
}
