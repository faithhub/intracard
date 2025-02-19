<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\PaymentSchedule;
use Carbon\Carbon;

class TestRemindersStatus extends Command
{
    protected $signature = 'reminders:status {schedule_id?}';
    protected $description = 'Check status of reminders for payment schedules';

    public function handle()
    {
        $scheduleId = $this->argument('schedule_id');
        $today = Carbon::now()->startOfDay();

        if ($scheduleId) {
            $schedules = PaymentSchedule::where('id', $scheduleId)->get();
        } else {
            $schedules = PaymentSchedule::where('status', 'active')->get();
        }

        foreach ($schedules as $schedule) {
            $this->info("\nChecking Schedule ID: {$schedule->id}");
            $this->info("Payment Type: {$schedule->payment_type}");
            $this->info("Amount: {$schedule->amount}");
            
            $reminderDates = json_decode($schedule->reminder_dates, true);
            
            if (empty($reminderDates)) {
                $this->warn("No reminder dates found for this schedule");
                continue;
            }

            $rows = [];
            foreach ($reminderDates as $paymentDate => $reminders) {
                // Skip if reminders is empty or not an array
                if (empty($reminders) || !is_array($reminders)) {
                    continue;
                }

                foreach ($reminders as $reminderType => $reminderDate) {
                    try {
                        $reminderCarbon = Carbon::parse($reminderDate);
                        $status = $reminderCarbon->isPast() ? 'Past' : 'Upcoming';
                        $daysUntil = $today->diffInDays($reminderCarbon, false);
                        
                        $rows[] = [
                            $paymentDate,
                            $reminderType,
                            $reminderDate,
                            $status,
                            $daysUntil
                        ];
                    } catch (\Exception $e) {
                        $this->error("Error processing reminder date: {$reminderDate} for payment date: {$paymentDate}");
                        continue;
                    }
                }
            }

            if (empty($rows)) {
                $this->warn("No valid reminders found for this schedule");
                continue;
            }

            $this->table(
                ['Payment Date', 'Reminder Type', 'Reminder Date', 'Status', 'Days Until/Past'],
                $rows
            );

            // Add payment status
            $completedPayments = \App\Models\Payment::where('schedule_id', $schedule->id)
                ->where('status', 'completed')
                ->pluck('due_date')
                ->toArray();

            if (!empty($completedPayments)) {
                $this->info("\nCompleted Payments:");
                foreach ($completedPayments as $paymentDate) {
                    $this->line("- {$paymentDate}");
                }
            }
        }
    }
}