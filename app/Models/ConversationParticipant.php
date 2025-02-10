<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationParticipant extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
    ];

    /**
     * Relationship to the Conversation.
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Relationship to the User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
