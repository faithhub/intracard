<?php
namespace App\Jobs;

use App\Models\Payment;
use App\Models\PaymentReminder;
use App\Models\PaymentSchedule;
use App\Models\User;
use App\Notifications\PaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// class PaymentReminderJob implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     public function __construct(
//         private User $user,
//         private PaymentSchedule $schedule,
//         private string $reminderType,
//         private Carbon $scheduledFor
//     ) {}

//     public function handle()
//     {
//         // Don't send reminder if payment is already made
//         if ($this->isPaymentComplete()) {
//             return;
//         }

//         // Send the reminder notification
//         $this->user->notify(new PaymentReminderNotification(
//             $this->schedule,
//             $this->reminderType
//         ));
//     }

//     private function isPaymentComplete()
//     {
//         return Payment::where('user_id', $this->user->id)
//             ->where('schedule_id', $this->schedule->id)
//             ->where('due_date', $this->scheduledFor->format('Y-m-d'))
//             ->where('status', 'completed')
//             ->exists();
//     }
// }

// app/Jobs/PaymentReminderJob.php
// class PaymentReminderJob implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     public $tries = 3;              // Number of retry attempts
// public $backoff = [60, 180, 300]; // Retry delays in seconds

//     public function __construct(
//         private User $user,
//         private PaymentSchedule $schedule,
//         private string $reminderType,
//         private Carbon $scheduledFor
//     ) {}

//     public function handle()
//     {
//         // Don't send reminder if payment is already made
//         if ($this->isPaymentComplete()) {
//             return;
//         }

//         // Check if this reminder is still valid according to stored reminder_dates
//         if (!$this->isValidReminder()) {
//             return;
//         }

//         // Send the reminder notification
//         $this->user->notify(new PaymentReminderNotification(
//             $this->schedule,
//             $this->reminderType
//         ));
//     }

//     private function isValidReminder(): bool
//     {
//         $reminderDates = json_decode($this->schedule->reminder_dates, true);
//         if (!$reminderDates) {
//             return false;
//         }

//         // Find the payment date this reminder belongs to
//         foreach ($reminderDates as $paymentDate => $reminders) {
//             if (isset($reminders[$this->reminderType])) {
//                 $storedReminderDate = Carbon::parse($reminders[$this->reminderType])->startOfDay();
//                 if ($storedReminderDate->eq($this->scheduledFor->startOfDay())) {
//                     return true;
//                 }
//             }
//         }

//         return false;
//     }

//     private function isPaymentComplete(): bool
//     {
//         return Payment::where('user_id', $this->user->id)
//             ->where('schedule_id', $this->schedule->id)
//             ->where('due_date', $this->scheduledFor->format('Y-m-d'))
//             ->where('status', 'completed')
//             ->exists();
//     }
// }

class PaymentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries   = 3;              // Number of retry attempts
    public $backoff = [60, 180, 300]; // Retry delays in seconds

    public function __construct(
        private User $user,
        private PaymentSchedule $schedule,
        private string $reminderType,
        private Carbon $scheduledFor,
        private ?string $paymentDate = null
    ) {}

    public function handle()
    {
        // Find the relevant reminder record
        $reminder = PaymentReminder::where('payment_schedule_id', $this->schedule->id)
            ->where('user_id', $this->user->id)
            ->where('reminder_type', $this->reminderType)
            ->where('reminder_date', $this->scheduledFor->format('Y-m-d'))
            ->first();

        // If no reminder record exists or it's not pending, skip
        if (! $reminder || $reminder->status !== 'pending') {
            return;
        }

        // If payment is already complete, mark reminder as cancelled and skip
        if ($reminder->payment_status === 'completed' || $this->isPaymentComplete($reminder)) {
            $reminder->update(['status' => 'cancelled']);
            return;
        }

        // Send the reminder notification
        $this->user->notify(new PaymentReminderNotification(
            $this->schedule,
            $this->reminderType
        ));

        // Mark the reminder as sent
        $reminder->markAsSent();
    }

    private function isPaymentComplete($reminder): bool
    {
        // Check if there's already a payment record
        return Payment::where(function ($query) use ($reminder) {
            $query->where('user_id', $this->user->id)
                ->where('schedule_id', $this->schedule->id)
                ->where('period_key', $reminder->period_key);
        })
            ->where('status', 'completed')
            ->exists();
    }
}
