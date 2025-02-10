<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class TeamMemberPaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $paymentSchedule;
    private $reminderType;
    private $dueDate;

    public function __construct($paymentSchedule, $reminderType)
    {
        $this->paymentSchedule = $paymentSchedule;
        $this->reminderType = $reminderType;
        $this->dueDate = $this->getNextPaymentDate();
        $this->onQueue('reminders');
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    private function getNextPaymentDate()
    {
        if ($this->paymentSchedule->frequency === 'bi-weekly') {
            return Carbon::now()->addWeeks(2);
        }
        return Carbon::now()->startOfMonth()->day($this->paymentSchedule->recurring_day);
    }

    private function getReminderSubject()
    {
        $type = ucfirst($this->paymentSchedule->payment_type);
        $frequency = $this->paymentSchedule->frequency ?? 'monthly';
        
        // Get days based on reminder type
        $days = $this->getDaysFromReminderType();
        
        return match($frequency) {
            'bi-weekly' => match($days) {
                5 => "Team Payment Reminder: Your {$type} Share Due in 5 Days",
                3 => "Team Payment Reminder: Your {$type} Share Due in 3 Days",
                2 => "Final Notice: Team {$type} Payment Due in 2 Days",
                default => "Team Payment Reminder"
            },
            'monthly' => match($days) {
                7 => "Team Payment Reminder: Your {$type} Share Due in 7 Days",
                5 => "Team Payment Reminder: Your {$type} Share Due in 5 Days",
                2 => "Final Notice: Team {$type} Payment Due in 2 Days",
                default => "Team Payment Reminder"
            },
            default => "Team Payment Reminder"
        };
    }

    private function getDaysFromReminderType()
    {
        // Extract the number from reminder type (e.g., '7_days_before' becomes 7)
        preg_match('/(\d+)_days_before/', $this->reminderType, $matches);
        return isset($matches[1]) ? (int)$matches[1] : null;
    }


    public function toMail($notifiable)
    {
        $totalAmount = $this->paymentSchedule->address->amount;
        $memberAmount = $this->paymentSchedule->amount;
        $percentage = ($memberAmount / $totalAmount) * 100;

        return (new MailMessage)
            ->subject($this->getReminderSubject())
            ->view('emails.reminders.team-member.' . $this->getReminderTemplate(), [
                'user' => $notifiable,
                'schedule' => $this->paymentSchedule,
                'amount' => number_format($memberAmount, 2),
                'totalAmount' => number_format($totalAmount, 2),
                'percentage' => number_format($percentage, 1),
                'dueDate' => $this->dueDate->format('M d, Y'),
                'paymentType' => $this->paymentSchedule->payment_type,
                'reminderType' => $this->reminderType,
                'teamName' => $this->paymentSchedule->team->name
            ]);
    }

    private function getReminderTemplate()
    {
        $days = $this->getDaysFromReminderType();
        $frequency = $this->paymentSchedule->frequency ?? 'monthly';
    
        return match($frequency) {
            'bi-weekly' => match($days) {
                5 => 'bi-weekly/initial',
                3 => 'bi-weekly/followup',
                2 => 'bi-weekly/final',
                default => 'default'
            },
            'monthly' => match($days) {
                7 => 'monthly/initial',
                5 => 'monthly/followup',
                2 => 'monthly/final',
                default => 'default'
            },
            default => 'default'
        };
    }

    public function tags()
    {
        return [
            'payment:' . $this->paymentSchedule->id,
            'type:' . $this->reminderType,
            'team:' . $this->paymentSchedule->team_id
        ];
    }
}