<?php

namespace Modules\ModuleRelease2\Providers\Concerns;

use Modules\ModuleRelease2\Models\Permission;
use Modules\ModuleRelease2\Models\Role;

trait ConfigurePermissions
{
    protected function configurePermissions(): void
    {
        $prefix = 'module_release_2_';
        $connection = 'module_release_2';

        config([
            'permission.models.role' => Role::class,
            'permission.models.permission' => Permission::class,
            'permission.table_names.roles' => $prefix . 'roles',
            'permission.table_names.permissions' => $prefix . 'permissions',
            'permission.table_names.model_has_roles' => $prefix . 'model_has_roles',
            'permission.table_names.model_has_permissions' => $prefix . 'model_has_permissions',
            'permission.table_names.role_has_permissions' => $prefix . 'role_has_permissions',
            'permission.connection' => $connection,
        ]);
    }
}
