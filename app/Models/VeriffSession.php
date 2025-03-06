<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VeriffSession extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'session_id',
        'vendor_data',
        'end_user_id',
        'payload',
        'email',
        'status',
        'webhook_payload' // Allow storing JSON payload
    ];

    protected $casts = [
        'webhook_payload' => 'array', // Automatically convert JSON to an array when retrieved
    ];
}

