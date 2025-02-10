<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BillHistory extends Model
{
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'uuid',
        'bill_id',
        'user_id',
        'card_id',
        'status',
        'provider',
        'amount',
        'due_date',
        'account_number',
        'frequency',
        'car_model',
        'car_year',
        'car_vin',
        'phone',
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Boot function for the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get the bill associated with the history.
     */
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    /**
     * Get the card used for the bill payment.
     */
    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    /**
     * Get the user who owns the bill history.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include active bill histories.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include bills due within the specified days.
     */
    public function scopeDueWithin($query, $days)
    {
        return $query->whereBetween('due_date', [now(), now()->addDays($days)]);
    }

    /**
     * Scope a query to only include overdue bills.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', 'active');
    }
}
