<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes;
    protected $fillable = ['key', 'name', 'value', 'is_show', 'type'];

    // You can cast values to the correct type automatically
    protected $casts = [
        'value' => 'string',
    ];
}
