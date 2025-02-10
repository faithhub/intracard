<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\User;
use App\Models\PaymentSchedule;
use App\Notifications\PaymentReminderNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PaymentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private User $user,
        private PaymentSchedule $schedule,
        private string $reminderType,
        private Carbon $scheduledFor
    ) {}

    public function handle()
    {
        // Don't send reminder if payment is already made
        if ($this->isPaymentComplete()) {
            return;
        }

        // Send the reminder notification
        $this->user->notify(new PaymentReminderNotification(
            $this->schedule,
            $this->reminderType
        ));
    }

    private function isPaymentComplete()
    {
        return Payment::where('user_id', $this->user->id)
            ->where('schedule_id', $this->schedule->id)
            ->where('due_date', $this->scheduledFor->format('Y-m-d'))
            ->where('status', 'completed')
            ->exists();
    }
}