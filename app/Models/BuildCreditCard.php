<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class BuildCreditCard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'card_id',
        'user_id',
        'cc_limit',
        'cc_due_date',
    ];

    /**
     * Automatically encrypt the credit card limit and due date on save.
     */
    protected $casts = [
        // 'cc_limit' => 'encrypted',
        // 'cc_due_date' => 'encrypted',
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
     * Define a relationship to the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a relationship to the card.
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
