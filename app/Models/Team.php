<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class Team extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'address_id',
        'admin_id',
        'name',
        'members',
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'name' => 'encrypted',
        'members' => 'encrypted', // JSON or long text data for members
    ];

    /**
     * Boot function for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID for each team
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    // In Team model
// public function creator()
// {
//     return $this->belongsTo(User::class, 'user_id');
// }
public function creator()
{
    return $this->belongsTo(User::class, 'user_id')
        ->select(['id', 'first_name', 'last_name', 'email', 'phone', 'account_goal', 'account_type', 'payment_setup', 'status', 'is_team', 'team_id']);
}

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }
    public function address()
{
    return $this->belongsTo(Address::class);
}
}
