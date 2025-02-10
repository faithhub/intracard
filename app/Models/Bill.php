<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'value',
        'status',
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var array<int, string>
     */
    protected $casts = [
        // 'name' => 'encrypted',
        // 'status' => 'encrypted',
    ];

    /**
     * Boot function for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID for each bill
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    public function allocations()
    {
        return $this->hasMany(WalletAllocation::class);
    }

}
