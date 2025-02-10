<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CardTransaction extends Model
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
        'card_id',
        'amount',
        'charge',
        'status',
        'type',
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var array<int, string>
     */
    protected $casts = [
        // 'amount' => 'encrypted',
        // 'charge' => 'encrypted',
        // 'status' => 'encrypted',
        // 'type' => 'encrypted',
    ];

    /**
     * Boot function for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID for each card transaction
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

}
