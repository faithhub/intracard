<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressEditLog extends Model
{
    protected $fillable = [
        'address_id',
        'user_id',
        'edited_at'
    ];

    protected $casts = [
        'edited_at' => 'datetime'
    ];
}
