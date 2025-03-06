<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentReminder extends Model
{
    protected $fillable = [
        'user_id',
        'schedule_id',
        'reminder_type',
        'reminder_date',
        'payment_date',
        'period_key',
    ];

    protected $casts = [
        'reminder_date' => 'date',
        'payment_date' => 'date',
    ];
}
