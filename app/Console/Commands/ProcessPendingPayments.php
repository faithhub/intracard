<?php
namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\PaymentSchedule;
use App\Notifications\PaymentFailedNotification;
use App\Notifications\PaymentReminderNotification;
use App\Notifications\TeamMemberPaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessPendingPayments extends Command
{
    protected $signature   = 'payments:process';
    protected $description = 'Check and process pending payments (rent, mortgage, bills)';

    /**
     * Handle the command execution
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->startOfDay();

        $this->info("Starting payment processing for " . $today->format('Y-m-d'));

        // Find all reminders due today
        $reminders = \App\Models\PaymentReminder::where('reminder_date', $today->format('Y-m-d'))
            ->where('status', 'pending')
            ->get();

        $this->info("Found {$reminders->count()} reminders due today");

        // Group reminders by schedule and type
        $individualReminders = $reminders->where('is_team_reminder', false);
        $teamReminders       = $reminders->where('is_team_reminder', true);

        $this->info("Processing {$individualReminders->count()} individual reminders");
        foreach ($individualReminders as $reminder) {
            $this->processReminder($reminder);
        }

        $this->info("Processing {$teamReminders->count()} team reminders");
        foreach ($teamReminders as $reminder) {
            $this->processTeamReminder($reminder);
        }

        $this->info('All pending payments and reminders have been processed.');

        return 0;
    }

    private function processReminder($reminder)
    {
        $schedule = $reminder->schedule;
        $user     = $reminder->user;

        if (! $schedule || ! $user) {
            $this->warn("Missing schedule or user for reminder {$reminder->id}");
            $reminder->update(['status' => 'cancelled']);
            return;
        }

        // Skip if user is soft-deleted
        if ($user->trashed()) {
            $this->warn("User {$user->id} has been soft-deleted, skipping reminder");
            $reminder->update(['status' => 'cancelled']);
            return;
        }

        // Check if payment is already complete
        if ($this->isPaymentComplete($reminder)) {
            $this->info("Payment already complete for {$reminder->payment_date}, skipping reminder");
            $reminder->update(['status' => 'cancelled', 'payment_status' => 'completed']);
            return;
        }

        // Try to process the payment for reminders close to due date
        if (in_array($reminder->reminder_type, ['2_days_before', '1_day_before'])) {
            $paymentProcessed = $this->processPayment($schedule, $reminder->payment_date);

            if ($paymentProcessed) {
                $this->info("Payment processed successfully, cancelling reminder");
                $reminder->update([
                    'status'         => 'cancelled',
                    'payment_status' => 'completed',
                ]);
                return;
            }
        }

        // Send the reminder notification
        try {
            $user->notify(new PaymentReminderNotification(
                $schedule,
                $reminder->reminder_type
            ));

            // Mark reminder as sent
            $reminder->markAsSent();

            $this->info("Sent {$reminder->reminder_type} reminder to {$user->email}");
        } catch (\Exception $e) {
            $this->error("Failed to send reminder {$reminder->id}: " . $e->getMessage());
            Log::error("Reminder sending failed", [
                'reminder_id' => $reminder->id,
                'user_id'     => $user->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);
        }
    }

    private function processTeamReminder($reminder)
    {
        $schedule = $reminder->schedule;
        $user     = $reminder->user;

        if (! $schedule || ! $user) {
            $this->warn("Missing schedule or user for team reminder {$reminder->id}");
            $reminder->update(['status' => 'cancelled']);
            return;
        }

        // Skip if user is soft-deleted
        if ($user->trashed()) {
            $this->warn("User {$user->id} has been soft-deleted, skipping team reminder");
            $reminder->update(['status' => 'cancelled']);
            return;
        }

        // Check if this user has already paid their portion
        if ($this->isPaymentComplete($reminder)) {
            $this->info("Payment already complete for {$reminder->payment_date}, skipping team reminder");
            $reminder->update(['status' => 'cancelled', 'payment_status' => 'completed']);
            return;
        }

        // Try to process payment for reminders close to due date
        if (in_array($reminder->reminder_type, ['2_days_before', '1_day_before'])) {
            $paymentProcessed = $this->processTeamMemberPayment($schedule, $user, $reminder);

            if ($paymentProcessed) {
                $this->info("Team member payment processed successfully, cancelling reminder");
                $reminder->update([
                    'status'         => 'cancelled',
                    'payment_status' => 'completed',
                ]);
                return;
            }
        }

        // Send the team reminder notification
        try {
            $user->notify(new TeamMemberPaymentReminderNotification(
                $schedule,
                $reminder->reminder_type
            ));

            // Mark reminder as sent
            $reminder->markAsSent();

            $this->info("Sent {$reminder->reminder_type} team reminder to {$user->email}");
        } catch (\Exception $e) {
            $this->error("Failed to send team reminder {$reminder->id}: " . $e->getMessage());
            Log::error("Team reminder sending failed", [
                'reminder_id' => $reminder->id,
                'user_id'     => $user->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);
        }
    }

    private function isPaymentComplete($reminder)
    {
        return Payment::where('user_id', $reminder->user_id)
            ->where('schedule_id', $reminder->payment_schedule_id)
            ->where('period_key', $reminder->period_key)
            ->where('status', 'completed')
            ->exists();
    }

    private function processTeamMemberPayment($schedule, $user, $reminder)
    {
        // Get team member's specific amount
        $teamMember = \App\Models\TeamMember::where([
            'team_id' => $schedule->team_id,
            'user_id' => $user->id,
        ])->first();

        if (! $teamMember) {
            $this->warn("No team member record found for user {$user->id}");
            return false;
        }

        // Calculate member's amount
        $totalAmount  = $schedule->address->amount ?? $schedule->amount;
        $percentage   = $teamMember->percentage ?? 0;
        $memberAmount = ($totalAmount * $percentage) / 100;

        // Custom logic for processing team member payment
        // This would be similar to processPayment but with the member's amount

        return false; // Placeholder - implement actual payment processing
    }

    private function processPayment($schedule, $paymentDate)
    {
        $today     = Carbon::now()->startOfDay();
        $periodKey = Payment::generatePeriodKey($schedule, $paymentDate);

        // Check if payment already exists
        if (Payment::existsForPeriod($schedule->id, $periodKey)) {
            $this->info("Payment already processed for schedule ID {$schedule->id} for period {$periodKey}");
            return true;
        }

        // Try to charge wallet first
        $walletSuccess = $this->chargeWallet($schedule);

        if ($walletSuccess) {
            // Create payment record with wallet payment method
            $payment = $this->createPaymentRecord(
                $schedule,
                $paymentDate,
                $periodKey,
                'wallet'
            );

            // Update all related reminders to prevent future notifications
            $this->updateRemindersAfterPayment($schedule->user_id, $schedule->id, $periodKey);

            return true;
        }

        // If wallet payment fails, try card as a fallback
        $cardSuccess = $this->chargeCard($schedule);

        if ($cardSuccess) {
            // Create payment record with card payment method
            $payment = $this->createPaymentRecord($schedule, $paymentDate, $periodKey, 'card');

            // Update all related reminders to prevent future notifications
            $this->updateRemindersAfterPayment($schedule->user_id, $schedule->id, $periodKey);

            return true;
        }

        // Both payment methods failed - handle failure
        Log::warning('All payment methods failed', [
            'schedule_id' => $schedule->id,
            'amount'      => $schedule->amount,
            'period_key'  => $periodKey,
        ]);

        // Send failure notification on last reminder date
        $this->sendFailureNotificationIfNeeded($schedule, $today);

        return false;
    }

    private function updateRemindersAfterPayment($userId, $scheduleId, $periodKey)
    {
        // Cancel all future reminders for this payment period
        \App\Models\PaymentReminder::where('user_id', $userId)
            ->where('payment_schedule_id', $scheduleId)
            ->where('period_key', $periodKey)
            ->where('reminder_date', '>=', now()->format('Y-m-d'))
            ->update([
                'status'         => 'cancelled',
                'payment_status' => 'completed',
            ]);
    }

    private function chargeWallet($schedule)
    {
        try {
            // Check if user has a wallet
            $user = $schedule->user;
            if (! $user || ! $user->wallet) {
                Log::info('User has no wallet', ['user_id' => $schedule->user_id ?? 'unknown']);
                return false;
            }

            $wallet = $user->wallet;

            // Find the specific allocation for this bill/payment type
            $allocation = $wallet->allocations()
                ->where('bill_id', $schedule->bill_id)
                ->first();

            if (! $allocation) {
                Log::info('No wallet allocation found for this bill', [
                    'user_id'      => $user->id,
                    'bill_id'      => $schedule->bill_id,
                    'payment_type' => $schedule->payment_type,
                ]);
                return false;
            }

            // Check if allocation has sufficient funds
            if ($allocation->remaining_amount < $schedule->amount) {
                Log::info('Insufficient allocation funds', [
                    'user_id'          => $user->id,
                    'allocation_id'    => $allocation->id,
                    'remaining_amount' => $allocation->remaining_amount,
                    'required_amount'  => $schedule->amount,
                ]);
                return false;
            }

            // Process the payment from allocation
            try {
                // Start transaction to ensure data consistency
                DB::beginTransaction();

                // Deduct from allocation
                $allocation->deductFunds($schedule->amount);

                // Create transaction record
                $transaction = $wallet->transactions()->create([
                    'user_id' => $user->id,
                    'bill_id' => $schedule->bill_id,
                    'amount'  => $schedule->amount,
                    'charge'  => 0, // No charge for wallet payments
                    'status'  => 'completed',
                    'type'    => 'payment',
                    'details' => json_encode([
                        'payment_type'  => $schedule->payment_type,
                        'schedule_id'   => $schedule->id,
                        'allocation_id' => $allocation->id,
                        'payment_for'   => $schedule->payment_type,
                    ]),
                ]);

                DB::commit();

                $this->info("Wallet payment for schedule ID {$schedule->id} was successful using allocation {$allocation->id}");
                return true;

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Wallet allocation payment failed during processing', [
                    'schedule_id'   => $schedule->id,
                    'user_id'       => $user->id,
                    'allocation_id' => $allocation->id,
                    'error'         => $e->getMessage(),
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Wallet payment failed', [
                'schedule_id' => $schedule->id,
                'user_id'     => $schedule->user_id ?? 'unknown',
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
    private function createPaymentRecord($schedule, $paymentDate, $periodKey, $paymentMethod, $allocationId = null)
    {
        $paymentData = [
            'user_id'               => $schedule->user_id,
            'team_id'               => $schedule->is_team_payment ? $schedule->team_id : null,
            'amount'                => $schedule->amount,
            'charges'               => 0,
            'payment_for'           => $schedule->payment_type,
            'bill_id'               => $schedule->bill_id,
            'due_date'              => $paymentDate,
            'payment_date'          => now()->toDateString(),
            'payment_method'        => $paymentMethod,
            'paid_at'               => now(),
            'payment_timing'        => 'on_time',
            'days_difference'       => 0,
            'status'                => 'completed',
            'is_team_payment'       => $schedule->is_team_payment,
            'transaction_reference' => "auto-{$paymentMethod}-" . uniqid(),
        ];

        // If this was a wallet payment, include the allocation ID in notes
        if ($paymentMethod === 'wallet' && $allocationId) {
            $paymentData['notes'] = json_encode([
                'wallet_allocation_id' => $allocationId,
                'payment_source'       => 'auto_debit',
            ]);
        }

        return Payment::updateOrCreate(
            ['schedule_id' => $schedule->id, 'period_key' => $periodKey],
            $paymentData
        );
    }
    private function sendFailureNotificationIfNeeded_old($schedule, $today)
    {
        $reminderDates    = json_decode($schedule->reminder_dates, true);
        $lastReminderDate = null;

        foreach ($reminderDates as $pDate => $reminders) {
            foreach ($reminders as $rType => $rDate) {
                if (strpos($rType, '1_days_before') !== false || strpos($rType, '2_days_before') !== false) {
                    $lastReminderDate = $rDate;
                }
            }
        }

        if ($lastReminderDate && Carbon::parse($lastReminderDate)->startOfDay()->eq($today)) {
            Notification::route('mail', $schedule->user->email)
                ->notify(new PaymentFailedNotification($schedule));

            $this->warn("Payment for schedule ID {$schedule->id} failed after final attempt. Notification sent.");

            Log::info('Payment failure notification sent', [
                'schedule_id' => $schedule->id,
                'user_id'     => $schedule->user_id,
                'email'       => $schedule->user->email,
            ]);
        }
    }

    private function sendFailureNotificationIfNeeded($reminder, $today = null)
    {
        // Only send failure notification for the final reminders
        if (in_array($reminder->reminder_type, ['1_days_before', '2_days_before'])) {
            Notification::route('mail', $reminder->user->email)
                ->notify(new PaymentFailedNotification($reminder->schedule));

            $this->warn("Payment for reminder ID {$reminder->id} failed after final attempt. Notification sent.");

            Log::info('Payment failure notification sent', [
                'reminder_id' => $reminder->id,
                'schedule_id' => $reminder->payment_schedule_id,
                'user_id'     => $reminder->user_id,
                'email'       => $reminder->user->email,
            ]);
        }
    }
    /**
     * Simulate card charging for the schedule (Replace with real gateway logic)
     *
     * @param PaymentSchedule $schedule
     * @return bool Whether charge was successful
     */
    private function chargeCard($schedule)
    {
        try {
            // Simulate a card charge (Replace with real payment gateway API call)
            // Example: $response = PaymentGateway::charge($schedule->user->card_token, $schedule->amount);

            // Simulate success/failure randomly (50% chance of success)
            $success = rand(0, 1) === 1;

            if ($success) {
                Log::info('Card charged successfully', [
                    'schedule_id' => $schedule->id,
                    'amount'      => $schedule->amount,
                ]);
            } else {
                Log::warning('Card charge failed', [
                    'schedule_id' => $schedule->id,
                    'amount'      => $schedule->amount,
                ]);
            }

            return $success;
        } catch (\Exception $e) {
            // Log any exceptions that occur during payment processing
            $this->error("Error charging card for schedule ID {$schedule->id}: {$e->getMessage()}");
            Log::error("Payment processing exception", [
                'schedule_id' => $schedule->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}
