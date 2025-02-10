<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class PaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

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
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    private function getNextPaymentDate()
    {
        if ($this->paymentSchedule->frequency === 'bi-weekly') {
            return Carbon::now()->addWeeks(2);
        }
        
        // For monthly payments
        return Carbon::now()->startOfMonth()->day($this->paymentSchedule->recurring_day);
    }

    private function getDaysFromReminderType()
    {
        preg_match('/(\d+)_days_before/', $this->reminderType, $matches);
        return isset($matches[1]) ? (int)$matches[1] : null;
    }

    private function getReminderSubject()
    {
        $type = ucfirst($this->paymentSchedule->payment_type);
        $frequency = $this->paymentSchedule->frequency ?? 'monthly';
        $days = $this->getDaysFromReminderType();
        
        return match($frequency) {
            'bi-weekly' => match($days) {
                5 => "Upcoming Payment Reminder: {$type} Due in 5 Days",
                3 => "Payment Due Soon: {$type} Due in 3 Days",
                2 => "Final Payment Notice: {$type} Due in 2 Days",
                default => "Payment Reminder"
            },
            'monthly' => match($days) {
                7 => "Upcoming Payment Reminder: {$type} Due in 7 Days",
                5 => "Payment Due Soon: {$type} Due in 5 Days",
                2 => "Final Payment Notice: {$type} Due in 2 Days",
                default => "Payment Reminder"
            },
            default => "Payment Reminder"
        };
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $days = $this->getDaysFromReminderType();
        $templateName = $this->getReminderTemplate();

        return (new MailMessage)
            ->subject($this->getReminderSubject())
            ->view('emails.reminders.' . $templateName, [
                'user' => $notifiable,
                'schedule' => $this->paymentSchedule,
                'amount' => number_format($this->paymentSchedule->amount, 2),
                'dueDate' => $this->dueDate->format('M d, Y'),
                'paymentType' => $this->paymentSchedule->payment_type,
                'daysRemaining' => $days,
                'frequency' => $this->paymentSchedule->frequency ?? 'monthly'
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
            'frequency:' . ($this->paymentSchedule->frequency ?? 'monthly')
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
