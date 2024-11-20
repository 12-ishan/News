<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $table = 'permission_group';

    public function permissionByGroup()
    {
        return $this->hasMany(PermissionHead::class, 'permission_group_id');
    }
}
