<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LandlordFinancerDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'address_id',
        'type',
        'payment_method',
        'landlordType',
        'details',
    ];

    /**
     * Automatically encrypt sensitive fields.
     */
    protected $casts = [
        'details' => 'encrypted', // Encrypt the details field
        // 'type' => 'encrypted',    // Encrypt the type field
        // 'payment_method' => 'encrypted',    // Encrypt the type field
        // 'landlordType' => 'encrypted',    // Encrypt the type field
    ];

    /**
     * Boot function to handle UUID generation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

        /**
     * Define a relationship to the address.
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
