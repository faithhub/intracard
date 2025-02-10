<?php

namespace App\Console\Commands;

use App\Models\PaymentSchedule;
use App\Notifications\PaymentFailedNotification;
use App\Notifications\PaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ProcessPendingPayments extends Command
{
    protected $signature = 'payments:process';
    protected $description = 'Check and process pending payments (rent, mortgage, bills)';

    public function handle()
    {
        $today = Carbon::now();

        // Fetch all active schedules that are within valid duration ranges
        $activeSchedules = PaymentSchedule::where('status', 'active')
            ->where(function ($query) use ($today) {
                $query->whereNull('duration_from') // No specific start date
                    ->orWhere('duration_from', '<=', $today->toDateString()); // Start date is today or earlier
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('duration_to') // No specific end date
                    ->orWhere('duration_to', '>=', $today->toDateString()); // End date is today or later
            })
            ->get();

        foreach ($activeSchedules as $schedule) {
            // Process payment and notifications for each schedule
            $this->processSchedule($schedule, $today);
        }

        $this->info('All pending payments and reminders have been processed.');
    }

    /**
     * Process a single payment schedule:
     * - Attempt payment first
     * - If payment fails, send a reminder notification
     */
    private function processSchedule($schedule, $today)
    {
        // Generate reminders dynamically based on the schedule
        $reminders = $schedule->generateReminders();

        // Iterate through each reminder date
        foreach ($reminders as $key => $date) {
            if ($today->isSameDay(Carbon::parse($date))) {
                // Attempt to process payment for the current reminder date
                $paymentSuccess = $this->processPayment($schedule, $date);

                if (!$paymentSuccess) {
                    // If payment fails, send a reminder notification to the user
                    Notification::route('mail', $schedule->user->email)
                        ->notify(new PaymentReminderNotification($schedule, $key));

                    $this->info("Reminder sent for schedule ID {$schedule->id} ({$key}).");
                }

                // If payment is successful, no notification is needed
                break; // Stop further processing for this schedule
            }
        }
    }

    /**
     * Process payment for a specific schedule and date:
     * - Mark the schedule as "paid" if payment succeeds
     * - Return true if payment succeeds, false otherwise
     */
    private function processPayment($schedule, $paymentDate)
    {
        if ($schedule->status === 'paid') {
            // Stop processing if already paid
            return true;
        }

        // Simulated Payment Gateway Logic (Replace with actual gateway logic)
        $success = $this->chargeCard($schedule);

        if ($success) {
            // Mark schedule as paid and stop further attempts
            $schedule->update(['status' => 'paid']);
            $this->info("Payment for schedule ID {$schedule->id} was successful.");

            // Cancel further attempts by skipping the rest of the reminders
            return;
        } else {
            // Notify user of payment failure if it's the last attempt (optional)
            if ($paymentDate === $schedule->generateReminders()['1_day_before']) {
                Notification::route('mail', $schedule->user->email)
                    ->notify(new PaymentFailedNotification($schedule));
                $this->warn("Payment for schedule ID {$schedule->id} failed after final attempt. Notification sent.");
            }
        }
    }

    /**
     * Simulate card charging for the schedule (Replace with real gateway logic)
     */
    private function chargeCard($schedule)
    {
        try {
            // Simulate a card charge (Replace with real payment gateway API call)
            // Example: $response = PaymentGateway::charge($schedule->user->card_token, $schedule->amount);

            // Simulate success/failure randomly (50% chance of success)
            return rand(0, 1) === 1;
        } catch (\Exception $e) {
            // Log any exceptions that occur during payment processing
            $this->error("Error charging card for schedule ID {$schedule->id}: {$e->getMessage()}");
            return false;
        }
    }
}
