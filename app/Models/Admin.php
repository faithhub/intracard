<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Ensure this is imported

class Admin extends Authenticatable
{
    use Notifiable;

    // Define the table if necessary
    protected $table = 'admins';
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'password',
        'otp_code',
        'otp_expires_at',
        'otp_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

       /**
     * Define the relationship to RoleAdmin.
     */
    public function roleAdmins()
    {
        return $this->hasMany(RoleAdmin::class);
    }

    /**
     * Define the relationship to Role through RoleAdmin.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_admins', 'admin_id', 'role_id');
    }

    /**
     * Check if the admin has a specific role.
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
}
