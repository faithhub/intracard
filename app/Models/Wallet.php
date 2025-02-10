<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Wallet extends Model
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
        'balance',
        'details',
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var array<int, string>
     */
    protected $casts = [
        // 'balance' => 'encrypted',
        'details' => 'encrypted', // JSON or encrypted details about the wallet
    ];

    /**
     * Boot function for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID for each wallet
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    // Add the relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
    public function allocations()
{
    return $this->hasMany(WalletAllocation::class);
}


}
