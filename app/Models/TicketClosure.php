<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class TicketClosure extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;  // Disable auto-incrementing
    protected $keyType = 'string'; // Set key type to string
    protected $fillable = [
        'ticket_id',
        'closed_by', 
        'resolution_status',
        'reason',
        'feedback',
        'rating'
    ];
    
    protected $casts = [
        'rating' => 'integer'
    ];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
