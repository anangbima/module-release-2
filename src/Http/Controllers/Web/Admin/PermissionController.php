<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Admin\StorePermissionRequest;
use Modules\ModuleRelease2\Http\Requests\Web\Admin\UpdatePermissionRequest;
use Modules\ModuleRelease2\Models\Permission;
use Modules\ModuleRelease2\Services\Admin\PermissionService;

class PermissionController extends Controller
{
    public function __construct(
       protected PermissionService $permissionService,
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = $this->permissionService->getAllPermissions();

        $data = [
            'title' => 'Permissions',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Permissions',
                    'url' => '#',
                ],
            ],
            'permissions' => $permissions,
        ];

        return view(module_release_2_meta('kebab').'::web.admin.permission.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Add New Permission',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Permissions',
                    'url' => route(module_release_2_meta('kebab').'.admin.permissions.index'),
                ],
                [
                    'name' => 'Add New Permission',
                    'url' => '#',
                ],
            ],
        ];

        return view(module_release_2_meta('kebab').'::web.admin.permission.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $this->permissionService->createPermission($request->validated());

        return redirect()->route(module_release_2_meta('kebab').'.admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $permission = $this->permissionService->getPermissionById($permission->id);

        $data = [
            'permission' => $permission,
            'title' => 'View Detail '. ucfirst($permission->name),
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Permissions',
                    'url' => route(module_release_2_meta('kebab').'.admin.permissions.index'),
                ],
                [
                    'name' => $permission->name,
                    'url' => '#',
                ],
            ],
        ];

        return view(module_release_2_meta('kebab').'::web.admin.permission.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $data = [
            'permission' => $permission,
            'title' => 'Edit Permission',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Permissions',
                    'url' => route(module_release_2_meta('kebab').'.admin.permissions.index'),
                ],
                [
                    'name' => 'Edit Permission',
                    'url' => '#',
                ],
            ],
        ];

        return view(module_release_2_meta('kebab').'::web.admin.permission.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $this->permissionService->updatePermission($permission->id, $request->validated());

        return redirect()->route(module_release_2_meta('kebab').'.admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $this->permissionService->deletePermission($permission->id);

        return redirect()->route(module_release_2_meta('kebab').'.admin.permissions.index')->with('success', 'Permission deleted successfully.');  
    }
}
