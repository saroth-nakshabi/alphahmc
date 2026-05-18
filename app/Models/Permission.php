<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = ['name', 'permission_category_id'];

    public function permissionCategory()
    {
        return $this->belongsTo(PermissionCategory::class);
    }
}
