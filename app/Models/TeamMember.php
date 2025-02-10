<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'team_id',
        'name',
        'email',
        'status',
        'role',
        'amount',
        'user_id',
        'percentage',
        'invitation_token',
        'invitation_expires_at',
        'declined_at',
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'invitation_expires_at' => 'datetime',
        // 'name' => 'encrypted',
        // 'email' => 'encrypted',
        // 'amount' => 'encrypted',
        // 'status' => 'encrypted',
        // 'role' => 'encrypted',
    ];
    protected $dates = [
        // ... existing date fields
        'declined_at'
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

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Changed from incorrect team_id, id
        // return $this->belongsTo(User::class, 'team_id', 'id');
    }
    
}
