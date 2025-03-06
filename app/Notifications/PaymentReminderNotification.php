<?php
namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $paymentSchedule;
    private $reminderType;
    private $dueDate;
    
    // Improved retry configuration
    public $tries = 3;
    public $backoff = [10, 60, 300]; // Increasing backoff times
    public $maxExceptions = 3;

    public function __construct($paymentSchedule, $reminderType)
    {
        $this->paymentSchedule = $paymentSchedule;
        $this->reminderType = $reminderType;
        
        try {
            $this->dueDate = $this->getNextPaymentDate();
        } catch (\Exception $e) {
            Log::error('Error calculating payment due date', [
                'error' => $e->getMessage(),
                'schedule_id' => $paymentSchedule->id ?? 'unknown'
            ]);
            // Default to 30 days from now as fallback
            $this->dueDate = Carbon::now()->addDays(30);
        }
        
        $this->afterCommit = true;
        // Don't call onQueue here - should be set when dispatching
    }
    
    public function via(object $notifiable): array
    {
        Log::info('Payment reminder via method called', [
            'user_id' => $notifiable->id ?? 'unknown',
            'email' => $notifiable->email ?? 'unknown',
            'schedule_id' => $this->paymentSchedule->id ?? 'unknown'
        ]);
        
        return ['mail'];
    }

    private function getNextPaymentDate()
    {
        Log::info('Calculating payment due date', [
            'frequency' => $this->paymentSchedule->frequency ?? 'unknown',
            'recurring_day' => $this->paymentSchedule->recurring_day ?? 'unknown'
        ]);
        
        if ($this->paymentSchedule->frequency === 'bi-weekly') {
            return Carbon::now()->addWeeks(2);
        }

        // Handle case where recurring_day might be invalid
        $day = $this->paymentSchedule->recurring_day ?? 1;
        $day = min(max(1, $day), 28); // Keep day between 1-28 to avoid month boundary issues
        
        return Carbon::now()->startOfMonth()->day($day);
    }

    private function getDaysFromReminderType()
    {
        try {
            preg_match('/(\d+)_days_before/', $this->reminderType, $matches);
            return isset($matches[1]) ? (int) $matches[1] : null;
        } catch (\Exception $e) {
            Log::error('Error parsing reminder type', [
                'error' => $e->getMessage(),
                'reminder_type' => $this->reminderType ?? 'unknown'
            ]);
            return null;
        }
    }

    private function getReminderSubject()
    {
        try {
            $type = ucfirst($this->paymentSchedule->payment_type ?? 'payment');
            $frequency = $this->paymentSchedule->frequency ?? 'monthly';
            $days = $this->getDaysFromReminderType();

            return match ($frequency) {
                'bi-weekly' => match ($days) {
                    5 => "Upcoming Payment Reminder: {$type} Due in 5 Days",
                    3 => "Payment Due Soon: {$type} Due in 3 Days",
                    2 => "Final Payment Notice: {$type} Due in 2 Days",
                    default => "Payment Reminder"
                },
                'monthly' => match ($days) {
                    7 => "Upcoming Payment Reminder: {$type} Due in 7 Days",
                    5 => "Payment Due Soon: {$type} Due in 5 Days",
                    2 => "Final Payment Notice: {$type} Due in 2 Days",
                    default => "Payment Reminder"
                },
                default => "Payment Reminder"
            };
        } catch (\Exception $e) {
            Log::error('Error generating reminder subject', [
                'error' => $e->getMessage(),
                'reminder_type' => $this->reminderType ?? 'unknown'
            ]);
            
            // Return a default subject as fallback
            return "Payment Reminder";
        }
    }

    public function toMail($notifiable)
    {
        try {
            Log::info('Building payment reminder email', [
                'user_id' => $notifiable->id ?? 'unknown',
                'email' => $notifiable->email ?? 'unknown',
                'schedule_id' => $this->paymentSchedule->id ?? 'unknown',
                'reminder_type' => $this->reminderType
            ]);
            
            // Calculate amount based on team membership
            $amount = $this->paymentSchedule->amount ?? 0;
            $teamMember = null;
            $percentage = 100;
            
            // Check if user is a team member
            if (isset($notifiable->team_id) && $notifiable->team_id) {
                try {
                    // Get team member details
                    $teamMember = \App\Models\TeamMember::where([
                        'user_id' => $notifiable->id,
                        'team_id' => $notifiable->team_id,
                        'status'  => 'accepted',
                    ])->first();

                    if ($teamMember) {
                        // Calculate their portion based on percentage
                        $percentage = $teamMember->percentage ?? 100;
                        $amount = $amount * ($percentage / 100);
                    }
                } catch (\Exception $e) {
                    Log::error('Error calculating team member amount', [
                        'error' => $e->getMessage(),
                        'user_id' => $notifiable->id
                    ]);
                    // Continue with full amount
                }
            }
            
            $template = 'emails.reminders.' . $this->getReminderTemplate();
            
            Log::info('Using email template', ['template' => $template]);
            
            return (new MailMessage)
                ->subject($this->getReminderSubject())
                ->view($template, [
                    'user' => $notifiable,
                    'schedule' => $this->paymentSchedule,
                    'amount' => number_format($amount, 2),
                    'total_amount' => number_format($this->paymentSchedule->amount ?? 0, 2),
                    'percentage' => $percentage,
                    'dueDate' => $this->dueDate->format('M d, Y'),
                    'paymentType' => $this->paymentSchedule->payment_type ?? 'payment',
                    'daysRemaining' => $this->getDaysFromReminderType(),
                    'frequency' => $this->paymentSchedule->frequency ?? 'monthly',
                    'is_team_member' => isset($notifiable->team_id) && $notifiable->team_id ? true : false,
                ]);
        } catch (\Exception $e) {
            Log::error('Error in payment reminder toMail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'schedule_id' => $this->paymentSchedule->id ?? 'unknown'
            ]);
            
            // Return a simplified fallback email instead of failing
            return (new MailMessage)
                ->subject('Payment Reminder')
                ->line('You have an upcoming payment due on ' . $this->dueDate->format('M d, Y'))
                ->line('Please log in to your account to make your payment.')
                ->line('If you have any questions, please contact support.');
        }
    }

    protected function getReminderTemplate()
    {
        try {
            $days = $this->getDaysFromReminderType();

            return match ($days) {
                7 => 'initial',
                5 => 'initial',
                3 => 'followup',
                2 => 'final',
                default => 'default'
            };
        } catch (\Exception $e) {
            Log::error('Error getting reminder template', [
                'error' => $e->getMessage(),
                'reminder_type' => $this->reminderType ?? 'unknown'
            ]);
            
            // Return a default template as fallback
            return 'default';
        }
    }

    public function tags()
    {
        try {
            return [
                'payment:' . ($this->paymentSchedule->id ?? 'unknown'),
                'type:' . ($this->reminderType ?? 'unknown'),
                'frequency:' . ($this->paymentSchedule->frequency ?? 'monthly'),
            ];
        } catch (\Exception $e) {
            Log::error('Error generating tags', [
                'error' => $e->getMessage()
            ]);
            return ['payment:reminder'];
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Payment reminder notification failed', [
            'error' => $exception->getMessage(),
            'payment_schedule_id' => $this->paymentSchedule->id ?? 'unknown',
            'reminder_type' => $this->reminderType ?? 'unknown',
            'trace' => $exception->getTraceAsString(),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            // No data needed here
        ];
    }
}