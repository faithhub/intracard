<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    // You can cast values to the correct type automatically
    protected $casts = [
        'value' => 'string',
    ];
}
