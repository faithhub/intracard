<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoReply extends Model
{
    protected $fillable = ['keywords', 'response', 'status'];

    // Ensure keywords are cast to an array
    protected $casts = [
        'keywords' => 'array',
    ];
}
