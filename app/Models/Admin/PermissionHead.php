<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class PermissionHead extends Model
{
    protected $table = 'permission';
    protected $primaryKey = 'id';

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }

    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }
}
