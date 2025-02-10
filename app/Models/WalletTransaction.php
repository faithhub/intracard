<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WalletTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'bill_id',
        'wallet_id',
        'amount',
        'charge',
        'status',
        'type',
        'details',
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var array<int, string>
     */
    protected $casts = [
        // 'amount' => 'encrypted',
        // 'charge' => 'encrypted',
        'details' => 'encrypted', // JSON or encrypted details about the transaction
        // 'status' => 'encrypted',
        // 'type' => 'encrypted',
    ];

    /**
     * Boot function for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID for each wallet transaction
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
}
