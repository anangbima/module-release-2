<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Services\Admin\UserService;
use Modules\DesaModuleTemplate\Services\Shared\LogActivityService;

class HomeController extends Controller
{
    public function __construct(
        protected LogActivityService $logActivityService,
        protected UserService $userService,
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUser();

        $data = [
            'title' => 'Dashboard',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Home',
                    'url' => '#',
                ],
            ],
            'recentActivity' => $this->logActivityService->getRecentLogs(),
            'totalUsers' => $users->count()
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.dashboard.index', $data);
    }
}
