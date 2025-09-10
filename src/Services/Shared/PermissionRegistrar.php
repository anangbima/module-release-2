<?php

namespace Modules\ModuleRelease2\Services\Shared;

use Modules\ModuleRelease2\Models\Permission;
use Modules\ModuleRelease2\Models\Role;
use Spatie\Permission\PermissionRegistrar as SpatiePermissionRegistrar;

class PermissionRegistrar extends SpatiePermissionRegistrar
{
    public function getPermissionClass(): string
    {
        return Permission::class;
    }

    public function getRoleClass(): string
    {
        return Role::class;
    }
}