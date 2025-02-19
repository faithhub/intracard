<?php

namespace App\Console\Commands;

use App\Models\PaymentSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestRemindersForDate extends Command
{
    protected $signature = 'reminders:test-date {date?}';
    protected $description = 'Test reminders for a specific date';

    public function handle()
    {
        $date = $this->argument('date') 
            ? Carbon::parse($this->argument('date'))->startOfDay()
            : Carbon::now()->startOfDay();

        $this->info("Testing reminders for: " . $date->format('Y-m-d'));

        $activeSchedules = PaymentSchedule::where('status', 'active')
            ->where('duration_from', '<=', $date)
            ->where('duration_to', '>=', $date)
            ->get();

        if ($activeSchedules->isEmpty()) {
            $this->warn("No active schedules found for this date.");
            return;
        }

        $foundReminders = false;

        foreach ($activeSchedules as $schedule) {
            $reminderDates = json_decode($schedule->reminder_dates, true);
            
            if (empty($reminderDates)) {
                continue;
            }

            foreach ($reminderDates as $paymentDate => $reminders) {
                // Skip if reminders is empty or not an array
                if (empty($reminders) || !is_array($reminders)) {
                    continue;
                }

                foreach ($reminders as $reminderType => $reminderDate) {
                    try {
                        $reminderCarbon = Carbon::parse($reminderDate)->startOfDay();
                        if ($reminderCarbon->eq($date)) {
                            $foundReminders = true;
                            $this->info("\nFound reminder:");
                            $this->info("Schedule ID: {$schedule->id}");
                            $this->info("Payment Type: {$schedule->payment_type}");
                            $this->info("Amount: {$schedule->amount}");
                            $this->info("Payment Date: {$paymentDate}");
                            $this->info("Reminder Type: {$reminderType}");
                            $this->info("Reminder Date: {$reminderDate}");
                            
                            // Check if payment is already made
                            $paymentExists = \App\Models\Payment::where('schedule_id', $schedule->id)
                                ->where('due_date', $paymentDate)
                                ->where('status', 'completed')
                                ->exists();
                                
                            $this->info("Payment Status: " . ($paymentExists ? "Completed" : "Pending"));
                            $this->line('-------------------');
                        }
                    } catch (\Exception $e) {
                        $this->error("Error processing reminder date for schedule {$schedule->id}: {$e->getMessage()}");
                    }
                }
            }
        }

        if (!$foundReminders) {
            $this->info("No reminders found for " . $date->format('Y-m-d'));
        }
    }
}