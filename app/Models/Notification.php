<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'admin_id', 'title', 'message', 'category', 'priority', 'is_read', 'is_archived', 'expires_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_read' => 'boolean',
        'is_archived' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Automatically encrypt the title when setting it.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = Crypt::encryptString($value);
    }

    /**
     * Automatically decrypt the title when accessing it.
     */
    public function getTitleAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * Automatically encrypt the message when setting it.
     */
    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = Crypt::encryptString($value);
    }

    /**
     * Automatically decrypt the message when accessing it.
     */
    public function getMessageAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
