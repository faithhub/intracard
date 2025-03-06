<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification
{
    use Queueable;

    private $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->subject('Payment Failed Notification')
    //         ->greeting("Hello {$notifiable->name},")
    //         ->line("We were unable to process your {$this->payment->payment_type} payment due on {$this->payment->payment_date}.")
    //         ->line("Please ensure your card or wallet is funded and try again.")
    //         ->action('Update Payment Method', url('/payment-method'))
    //         ->line('Thank you for using our service!');
    // }

    public function toMail($notifiable)
{
    $paymentType = $this->payment->payment_type ?? ($this->payment->payment_for ?? 'scheduled');
    $dueDate = $this->payment->payment_date ?? ($this->payment->due_date ?? 'upcoming');
    
    return (new MailMessage)
        ->subject('Payment Failed Notification')
        ->greeting("Hello {$notifiable->name},")
        ->line("We were unable to process your {$paymentType} payment due on {$dueDate}.")
        ->line("Please ensure your card or wallet is funded and try again.")
        ->action('Update Payment Method', url('/payment-method'))
        ->line('Thank you for using our service!');
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
