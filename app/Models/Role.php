<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{ 
    
    protected $fillable = ['name', 'slug', 'description'];

    /**

    * Define the relationship to RoleAdmin.
    */
   public function roleAdmins()
   {
       return $this->hasMany(RoleAdmin::class);
   }

   /**
    * Define the relationship to Admin through RoleAdmin.
    */
   public function admins()
   {
       return $this->belongsToMany(Admin::class, 'role_admins', 'role_id', 'admin_id');
   }
}
