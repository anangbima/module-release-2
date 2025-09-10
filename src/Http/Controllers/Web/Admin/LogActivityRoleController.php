<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\TestModule1\Models\LogActivity;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Models\Role;
use Modules\DesaModuleTemplate\Services\Admin\LogActivityService;
use Modules\DesaModuleTemplate\Services\Admin\RoleService;

class LogActivityRoleController extends Controller
{
    public function __construct(
        protected LogActivityService $logActivityService,
        protected RoleService $roleService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index(Role $role = null)
    {
        $roles = $this->roleService->getAllRoles();

        // Kalau tidak ada role dipilih, pakai role pertama
        $selectedRole = $role ?? $roles->first();

        $logs = $selectedRole 
            ? $this->logActivityService->getAllByRole($selectedRole->id) 
            : collect(); // kalau belum ada role sama sekali

        $data = [
            'title' => 'Log Activity By Role',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Log Activity By Role',
                    'url' => '#',
                ],
            ],
            'logs' => $logs,
            'selectedRole' => $selectedRole,
            'roles' => $roles,
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.log-activity.role.index', $data);
    }


}
