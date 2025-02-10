<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAdmin extends Model
{
    
    protected $table = 'role_admins';

    /**
     * Define the relationship to Admin.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Define the relationship to Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
