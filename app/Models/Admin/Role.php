<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';

    public function permissionHead(){
        return $this->belongsToMany(RolePermission::class, 'role_permission');
    }
}
