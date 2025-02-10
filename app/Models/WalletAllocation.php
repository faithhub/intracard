<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WalletAllocation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallet_allocations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'wallet_id',
        'bill_id',
        'allocated_amount',
        'spent_amount',
        'remaining_amount',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID for each bill
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    /**
     * Scope a query to only include allocations for a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include allocations for a specific wallet.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $walletId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForWallet($query, $walletId)
    {
        return $query->where('wallet_id', $walletId);
    }

    public function hasFunds()
    {
        return $this->remaining_amount > 0;
    }

    /**
     * Deduct a specified amount from the remaining funds.
     *
     * @param float $amount
     * @return void
     * @throws \Exception
     */
    public function deductFunds(float $amount)
    {
        if ($amount > $this->remaining_amount) {
            throw new \Exception("Insufficient funds for this allocation.");
        }

        $this->spent_amount += $amount;
        $this->remaining_amount -= $amount;
        $this->save();
    }

    /**
     * Add funds to this allocation.
     *
     * @param float $amount
     * @return void
     */
    public function addFunds(float $amount)
    {
        $this->allocated_amount += $amount;
        $this->remaining_amount += $amount;
        $this->save();
    }
}
