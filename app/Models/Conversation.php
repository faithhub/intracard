<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{  
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by', // The user who initiated the conversation
    ];

    /**
     * Relationships and additional methods can go here.
     */
    public function participants()
    {
        return $this->hasMany(ConversationParticipant::class);
    }
}
