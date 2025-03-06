<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'team_id',
        'amount',
        'charges',
        'payment_for',
        'bill_id',
        'due_date',
        'payment_date',
        'payment_method',
        'card_id',
        'wallet_id',
        'transaction_reference',
        'status',
        'is_team_payment',
        'notes',
        'period_key',
        'paid_at',
        'payment_timing',
        'days_difference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'charges' => 'decimal:2',
        'is_team_payment' => 'boolean',
        'due_date' => 'date',
        'payment_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Mark a payment as complete and calculate timing metrics
     *
     * @param string|null $transactionReference
     * @return bool
     */
    public function markAsComplete($transactionReference = null)
    {
        $now = Carbon::now();
        $dueDate = Carbon::parse($this->due_date);
        
        // Calculate days difference (negative = early, positive = late)
        $daysDifference = $now->diffInDays($dueDate, false);
        
        // Determine payment timing category
        $paymentTiming = 'on_time';
        if ($daysDifference < -1) { // More than 1 day early
            $paymentTiming = 'early';
        } elseif ($daysDifference > 1) { // More than 1 day late
            $paymentTiming = 'late';
        }
        
        // Update payment fields
        $this->status = 'completed';
        $this->payment_date = $now->toDateString();
        $this->paid_at = $now;
        $this->payment_timing = $paymentTiming;
        $this->days_difference = $daysDifference;
        
        if ($transactionReference) {
            $this->transaction_reference = $transactionReference;
        }
        
        return $this->save();
    }
    
    /**
     * Generate a unique period key based on payment schedule and due date
     *
     * @param PaymentSchedule $schedule
     * @param Carbon|string $dueDate
     * @return string
     */
    public static function generatePeriodKey($schedule, $dueDate)
    {
        if (!$dueDate instanceof Carbon) {
            $dueDate = Carbon::parse($dueDate);
        }
        
        if ($schedule->frequency === 'monthly') {
            return $schedule->id . '-' . $dueDate->format('Y-m');
        } elseif ($schedule->frequency === 'bi-weekly') {
            // For bi-weekly, use the actual due date as the period identifier
            return $schedule->id . '-' . $dueDate->format('Y-m-d');
        } else {
            // Default case for other frequencies
            return $schedule->id . '-' . $dueDate->format('Y-m-d');
        }
    }
    
    /**
     * Check if a payment exists for the given schedule and period
     *
     * @param int $scheduleId
     * @param string $periodKey
     * @return bool
     */
    public static function existsForPeriod($scheduleId, $periodKey)
    {
        return self::where('schedule_id', $scheduleId)
            ->where('period_key', $periodKey)
            ->where('status', 'completed')
            ->exists();
    }
    
    /**
     * Get payment statistics for a user
     *
     * @param int $userId
     * @return array
     */
    public static function getUserPaymentStats($userId)
    {
        $totalPayments = self::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
            
        $earlyPayments = self::where('user_id', $userId)
            ->where('payment_timing', 'early')
            ->count();
            
        $onTimePayments = self::where('user_id', $userId)
            ->where('payment_timing', 'on_time')
            ->count();
            
        $latePayments = self::where('user_id', $userId)
            ->where('payment_timing', 'late')
            ->count();
        
        $avgDaysDifference = self::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('days_difference')
            ->avg('days_difference');
        
        return [
            'total_payments' => $totalPayments,
            'early_payments' => $earlyPayments,
            'on_time_payments' => $onTimePayments,
            'late_payments' => $latePayments,
            'early_payment_percent' => $totalPayments > 0 ? ($earlyPayments / $totalPayments) * 100 : 0,
            'on_time_payment_percent' => $totalPayments > 0 ? ($onTimePayments / $totalPayments) * 100 : 0,
            'late_payment_percent' => $totalPayments > 0 ? ($latePayments / $totalPayments) * 100 : 0,
            'avg_days_difference' => $avgDaysDifference
        ];
    }

    // Existing relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(PaymentSchedule::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}