<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
        'sender_type',
        'read_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'read_at', // Add read_at to dates
    ];

    // Helper method to check if a message is read
    public function isRead()
    {
        return $this->read_at !== null;
    }

    // Mark message as read
    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
        
        return $this;
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
        return $this->sender_type === 'admin' 
        ? $this->belongsTo(Admin::class, 'sender_id')
        : $this->belongsTo(User::class, 'sender_id');

    //     if ($this->sender_id && Auth::guard('admin')->check() && Auth::guard('admin')->id() == $this->sender_id) {
    //     return $this->belongsTo(Admin::class, 'sender_id');
    // } else {
    //     return $this->belongsTo(User::class, 'sender_id');
    // }
        // return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relationship with the Ticket the message belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}