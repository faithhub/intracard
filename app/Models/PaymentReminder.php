<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    protected $fillable = [
        'payment_schedule_id',
        'user_id',
        'team_id',
        'payment_date',
        'reminder_date',
        'reminder_type',
        'status',
        'amount',
        'charges',
        'payment_status',
        'sent_at',
        'payment_completed_at',
        'period_key',
        'is_team_reminder',
        'payment_id',
    ];
    
    
    protected $casts = [
        'payment_date' => 'date',
        'reminder_date' => 'date',
        'sent_at' => 'datetime',
        'payment_completed_at' => 'datetime',
        'is_team_reminder' => 'boolean'
    ];
    
    // Add a method to mark payment as complete
    public function markPaymentComplete($paymentId)
    {
        // Get the actual payment to capture final amount and charges
        $payment = Payment::find($paymentId);
        
        $this->payment_status = 'completed';
        $this->payment_completed_at = now();
        $this->payment_id = $paymentId;
        
        // Update with actual values from the payment
        if ($payment) {
            $this->amount = $payment->amount;
            $this->charges = $payment->charges;
        }
        
        $this->save();
        
        // Cancel all future reminders for this payment
        static::where('payment_schedule_id', $this->payment_schedule_id)
            ->where('user_id', $this->user_id)
            ->where('payment_date', $this->payment_date)
            ->where('reminder_date', '>', now())
            ->update(['status' => 'cancelled']);
    }
    
    public function schedule()
    {
        return $this->belongsTo(PaymentSchedule::class, 'payment_schedule_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
    // Scope to find reminders due today
    public function scopeDueToday($query)
    {
        return $query->where('reminder_date', now()->toDateString())
                    ->where('status', 'pending');
    }
    
    // Mark as sent
    public function markAsSent()
    {
        $this->status = 'sent';
        $this->sent_at = now();
        $this->save();
    }
}
