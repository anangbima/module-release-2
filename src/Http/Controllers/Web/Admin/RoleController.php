<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Admin\StoreRoleRequest;
use Modules\DesaModuleTemplate\Http\Requests\Web\Admin\UpdateRoleRequest;
use Modules\DesaModuleTemplate\Models\Role;
use Modules\DesaModuleTemplate\Services\Admin\PermissionService;
use Modules\DesaModuleTemplate\Services\Admin\RoleService;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
        protected PermissionService $permissionService,
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->roleService->getAllRoles();

        $data = [
            'title' => 'Roles',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Roles',
                    'url' => '#',
                ],
            ],
            'roles' => $roles,
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.role.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Add New Role',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Roles',
                    'url' => route(desa_module_template_meta('kebab').'.admin.roles.index'),
                ],
                [
                    'name' => 'Add New Role',
                    'url' => '#',
                ],
            ],
            'availablePermissions' => $this->permissionService->getAllPermissions(),
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.role.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $this->roleService->createRole($request->validated());

        return redirect()->route(desa_module_template_meta('kebab').'.admin.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role = $this->roleService->getRoleById($role->id);

        $data = [
            'role' => $role,
            'title' => "View Detail ". ucfirst($role->name),
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Roles',
                    'url' => route(desa_module_template_meta('kebab').'.admin.roles.index'),
                ],
                [
                    'name' => $role->name,
                    'url' => '#',
                ],
            ],
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.role.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role = $this->roleService->getRoleById($role->id);

        $data = [
            'title' => 'Edit Role',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Roles',
                    'url' => route(desa_module_template_meta('kebab').'.admin.roles.index'),
                ],
                [
                    'name' => 'Edit Role',
                    'url' => '#',
                ],
            ],
            'role' => $role,
            'availablePermissions' => $this->permissionService->getAllPermissions(),
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.role.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->roleService->updateRole($role->id, $request->validated());

        return redirect()->route(desa_module_template_meta('kebab').'.admin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->roleService->deleteRole($role->id);

        return redirect()->route(desa_module_template_meta('kebab').'.admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
