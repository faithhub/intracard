<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'password',
        'otp_code',
        'profile_picture',
        'otp_expires_at',
        'otp_verified',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Existing relationships
    public function roleAdmins()
    {
        return $this->hasMany(RoleAdmin::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_admins', 'admin_id', 'role_id');
    }

    // Enhanced role checking methods
    public function hasRole($roleName)
    {
        return $this->roles()->where('slug', $roleName)->exists();
    }

    public function hasAnyRole(array $roleNames)
    {
        return $this->roles()->whereIn('slug', $roleNames)->exists();
    }

    public function hasAllRoles(array $roleNames)
    {
        return $this->roles()->whereIn('slug', $roleNames)->count() === count($roleNames);
    }

    // Helper method to determine if admin can perform specific actions
    public function canPerformAction($action)
    {
        $permissionMap = [
            'delete_user' => ['system_admin'],
            'edit_user' => ['system_admin', 'admin'],
            'view_user' => ['system_admin', 'admin', 'support', 'user_manager'],
            // Add more actions as needed
        ];

        return isset($permissionMap[$action]) && $this->hasAnyRole($permissionMap[$action]);
    }

    public function getProfilePictureUrlAttribute()
    {
        if (!$this->profile_picture) {
            return asset('assets/images/default-avatar.png');
        }
        
        return Storage::url($this->profile_picture);
    }

       
    /**
     * Get the full name of the admin
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}