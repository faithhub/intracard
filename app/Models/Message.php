<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'sender_id',
        'message',
        'file_path',
        'file_name',
    ];

    // Helper method to check if a message is read
    public function isRead()
    {
        return $this->read_at !== null;
    }

     // Encrypt message before saving
     public function setMessageAttribute($value)
     {
         $this->attributes['message'] = $value ? Crypt::encryptString($value) : null;
     }
 
     // Decrypt message when accessing
     public function getMessageAttribute($value)
     {
         return $value ? Crypt::decryptString($value) : null;
     }
     public function sender()
     {
         return $this->belongsTo(User::class, 'sender_id');
     }
 
     /**
      * Relationship with the Ticket the message belongs to.
      */
     public function ticket()
     {
         return $this->belongsTo(Ticket::class, 'ticket_id');
     }
}
