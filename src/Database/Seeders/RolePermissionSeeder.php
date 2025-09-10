<?php

namespace Modules\ModuleRelease2\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ModuleRelease2\Models\Role;
use Modules\ModuleRelease2\Services\Shared\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(PermissionRegistrar $registrar)
    {
        // Dapatkan model permission dari registrar
        $permissionModel = $registrar->getPermissionClass();

        // Roles
        $roles = ['admin', 'user'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'module_release_2_web',
            ]);
        }

        // Permissions
        $permissions = collect([
            ['name' => 'user create', 'module_name' => 'user'],
            ['name' => 'user update', 'module_name' => 'user'],
            ['name' => 'user delete', 'module_name' => 'user'],
            ['name' => 'user show', 'module_name' => 'user'],
            ['name' => 'user index', 'module_name' => 'user'],
        ]);

        foreach ($permissions as $perm) {
            $permissionModel::firstOrCreate(
                [
                    'name' => $perm['name'],
                    'guard_name' => 'module_release_2_web',
                ],
                [
                    'module_name' => $perm['module_name'],
                ]
            );
        }

        // Hubungkan semua permission ke admin pakai model instance langsung
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $allPermissions = $permissionModel::all();
            $admin->syncPermissions($allPermissions); // pakai instance langsung
        }
    }
}
