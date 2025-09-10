<?php

namespace Modules\DesaModuleTemplate\Services\Shared;

use Modules\DesaModuleTemplate\Models\Permission;
use Modules\DesaModuleTemplate\Models\Role;
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