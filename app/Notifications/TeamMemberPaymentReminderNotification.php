<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TeamMemberPaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $paymentSchedule;
    private $reminderType;
    private $dueDate;
    
    // Set proper retry configuration
    public $tries = 3;
    public $backoff = [10, 60, 300]; // Increasing backoff times

    public function __construct($paymentSchedule, $reminderType)
    {
        $this->paymentSchedule = $paymentSchedule;
        $this->reminderType = $reminderType;
        
        try {
            $this->dueDate = $this->getNextPaymentDate();
        } catch (\Exception $e) {
            Log::error('Error calculating due date', [
                'error' => $e->getMessage(),
                'schedule_id' => $paymentSchedule->id ?? 'unknown'
            ]);
            // Default to 30 days from now as fallback
            $this->dueDate = Carbon::now()->addDays(30);
        }
        
        // Don't call onQueue here - should be set when dispatching
    }

    public function via(object $notifiable): array
    {
        Log::info('Team payment reminder via method called', [
            'user_id' => $notifiable->id ?? 'unknown',
            'email' => $notifiable->email ?? 'unknown',
            'schedule_id' => $this->paymentSchedule->id ?? 'unknown'
        ]);
        
        return ['mail'];
    }

    private function getNextPaymentDate()
    {
        Log::info('Calculating next payment date', [
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

    private function getReminderSubject()
    {
        try {
            $type = ucfirst($this->paymentSchedule->payment_type ?? 'payment');
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
        } catch (\Exception $e) {
            Log::error('Error generating reminder subject', [
                'error' => $e->getMessage(),
                'reminder_type' => $this->reminderType ?? 'unknown'
            ]);
            
            // Return a default subject as fallback
            return "Payment Reminder";
        }
    }

    private function getDaysFromReminderType()
    {
        // Extract the number from reminder type (e.g., '7_days_before' becomes 7)
        preg_match('/(\d+)_days_before/', $this->reminderType, $matches);
        return isset($matches[1]) ? (int)$matches[1] : null;
    }

    public function toMail($notifiable)
    {
        try {
            Log::info('Building team payment reminder email', [
                'user_id' => $notifiable->id ?? 'unknown',
                'schedule_id' => $this->paymentSchedule->id ?? 'unknown',
                'reminder_type' => $this->reminderType ?? 'unknown'
            ]);
            
            // Safely access properties with null coalescing
            if (!isset($this->paymentSchedule->address)) {
                Log::error('Payment schedule address is null', [
                    'schedule_id' => $this->paymentSchedule->id ?? 'unknown'
                ]);
                throw new \Exception('Payment schedule address is missing');
            }
            
            if (!isset($this->paymentSchedule->team)) {
                Log::error('Payment schedule team is null', [
                    'schedule_id' => $this->paymentSchedule->id ?? 'unknown'
                ]);
                throw new \Exception('Payment schedule team is missing');
            }
            
            // Get the total amount from the address 
            $totalAmount = $this->paymentSchedule->address->amount ?? 0;
            
            // Find the team member record to get their percentage
            $teamMember = \App\Models\TeamMember::where([
                'team_id' => $this->paymentSchedule->team_id,
                'user_id' => $notifiable->id
            ])->first();
            
            // Calculate member's amount based on their percentage
            $percentage = $teamMember ? ($teamMember->percentage ?? 0) : 0;
            $memberAmount = ($totalAmount * $percentage) / 100;
            
            // Find the specific reminder record to get their amount
            $reminder = \App\Models\PaymentReminder::where([
                'payment_schedule_id' => $this->paymentSchedule->id,
                'user_id' => $notifiable->id,
                'reminder_type' => $this->reminderType,
                'payment_date' => $this->dueDate->format('Y-m-d')
            ])->first();
            
            // If we have a reminder record with an amount, use that
            if ($reminder && $reminder->amount > 0) {
                $memberAmount = $reminder->amount;
            }
            
            $template = 'emails.reminders.team-member.' . $this->getReminderTemplate();
            
            // Log details about the calculation
            Log::info('Team payment calculation', [
                'total_amount' => $totalAmount,
                'member_percentage' => $percentage,
                'calculated_amount' => $memberAmount,
                'template' => $template
            ]);
            
            return (new MailMessage)
                ->subject($this->getReminderSubject())
                ->view($template, [
                    'user' => $notifiable,
                    'schedule' => $this->paymentSchedule,
                    'amount' => number_format($memberAmount, 2),
                    'totalAmount' => number_format($totalAmount, 2),
                    'percentage' => number_format($percentage, 1),
                    'dueDate' => $this->dueDate->format('M d, Y'),
                    'paymentType' => $this->paymentSchedule->payment_type ?? 'payment',
                    'reminderType' => $this->reminderType,
                    'teamName' => $this->paymentSchedule->team->name ?? 'Your Team'
                ]);
                
        } catch (\Exception $e) {
            Log::error('Error in team payment reminder toMail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'schedule_id' => $this->paymentSchedule->id ?? 'unknown',
                'reminder_type' => $this->reminderType ?? 'unknown'
            ]);
            
            // Return a simplified fallback email instead of failing
            return (new MailMessage)
                ->subject('Payment Reminder')
                ->line('You have an upcoming team payment due on ' . $this->dueDate->format('M d, Y'))
                ->line('Please log in to your account to make your payment.')
                ->line('If you have any questions, please contact support.');
        }
    }

    private function getReminderTemplate()
    {
        try {
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
        return [
            'payment:' . ($this->paymentSchedule->id ?? 'unknown'),
            'type:' . ($this->reminderType ?? 'unknown'),
            'team:' . ($this->paymentSchedule->team_id ?? 'unknown')
        ];
    }
    
    /**
     * Handle notification failure
     */
    public function failed(\Exception $exception)
    {
        Log::error('Team payment reminder notification failed', [
            'error' => $exception->getMessage(),
            'payment_schedule_id' => $this->paymentSchedule->id ?? 'unknown',
            'reminder_type' => $this->reminderType ?? 'unknown',
            'trace' => $exception->getTraceAsString()
        ]);
    }
}