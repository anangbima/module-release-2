<?php

namespace Modules\DesaModuleTemplate\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DesaModuleTemplate\Models\Role;
use Modules\DesaModuleTemplate\Services\Shared\PermissionRegistrar;

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
                'guard_name' => 'desa_module_template_web',
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
                    'guard_name' => 'desa_module_template_web',
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
