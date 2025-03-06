<?php
namespace App\Jobs;

use App\Models\Payment;
use App\Models\PaymentReminder;
use App\Models\PaymentSchedule;
use App\Models\User;
use App\Notifications\TeamMemberPaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// class TeamMemberPaymentReminderJob implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
// // Add to both Job classes
//     public $tries   = 3;              // Number of retry attempts
//     public $backoff = [60, 180, 300]; // Retry delays in seconds

//     public function __construct(
//         private User $user,
//         private PaymentSchedule $schedule,
//         private string $reminderType,
//         private Carbon $scheduledFor
//     ) {}

//     public function handle()
//     {
//         // Check both individual payment and team payment status
//         if ($this->isPaymentComplete() || $this->isTeamPaymentComplete()) {
//             return;
//         }

//         // Send the reminder notification
//         $this->user->notify(new TeamMemberPaymentReminderNotification(
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

//     private function isTeamPaymentComplete()
//     {
//         return Payment::where('team_id', $this->schedule->team_id)
//             ->where('due_date', $this->scheduledFor->format('Y-m-d'))
//             ->where('status', 'completed')
//             ->where('is_team_payment', true)
//             ->exists();
//     }
// }

class TeamMemberPaymentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 3;              // Number of retry attempts
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
            ->where('is_team_reminder', true)
            ->first();
        
        // If no reminder record exists or it's not pending, skip
        if (!$reminder || $reminder->status !== 'pending') {
            return;
        }
        
        // Check if this user has already paid their portion
        if ($reminder->payment_status === 'completed' || $this->isPaymentComplete($reminder)) {
            $reminder->update(['status' => 'cancelled']);
            return;
        }

        // Send the reminder notification
        $this->user->notify(new TeamMemberPaymentReminderNotification(
            $this->schedule,
            $this->reminderType
        ));
        
        // Mark the reminder as sent
        $reminder->markAsSent();
    }

    private function isPaymentComplete($reminder)
    {
        // Only check for individual payments from this specific user
        return Payment::where('user_id', $this->user->id)
            ->where('schedule_id', $this->schedule->id)
            ->where('period_key', $reminder->period_key)
            ->where('status', 'completed')
            ->exists();
    }
}