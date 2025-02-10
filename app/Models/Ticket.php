<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'created_by',
        'subject',
        'description',
        'status',
    ];
    protected $casts = [
        'subject' => 'encrypted',
        'description' => 'encrypted',
    ];

    /**
     * Boot function for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID for each team member
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function participants()
{
    return $this->belongsToMany(User::class, 'ticket_participants');
}

public function closure()
{
    return $this->hasOne(TicketClosure::class);
}
}