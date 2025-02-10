<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'token',
        'status',
        'type',
        'name_on_card',
        'expiry_month',
        'expiry_year',
        'cvv',
        'is_primary',
        'card_number', // To store the masked card number
    ];

    /**
     * Automatically encrypt specified fields.
     */
    protected $casts = [
        'type' => 'encrypted',
        'token' => 'encrypted',
        'cvv' => 'encrypted',
        // 'status' => 'encrypted',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID for each bill
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    /**
     * Automatically save the masked card number when saving a token.
     */
    public function setTokenAttribute($value)
    {
        // Encrypt the token
        $this->attributes['token'] = $value;

        // Save the masked card number
        $this->attributes['card_number'] = '**** **** **** ' . substr($value, -4);
    }

    /**
     * Mask CVV when accessed.
     */
    public function getMaskedCvvAttribute()
    {
        return '***';
    }

    /**
     * Helper method to mark a card as primary.
     */
    public function markAsPrimary()
    {
        // Set all other cards for the user to `is_primary = false`
        self::where('user_id', $this->user_id)->update(['is_primary' => false]);

        // Set the current card as primary
        $this->update(['is_primary' => true]);
    }

    public function transactions()
    {
        return $this->hasMany(CardTransaction::class);
    }

}
