<?php

namespace App\Models;

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
        'transaction_reference',
        'status',
        'is_team_payment',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'charges' => 'decimal:2',
        'is_team_payment' => 'boolean',
    ];

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

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}