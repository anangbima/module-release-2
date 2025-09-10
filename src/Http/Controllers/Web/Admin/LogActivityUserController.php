<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Services\Admin\LogActivityService;

class LogActivityUserController extends Controller
{
    public function __construct(
        protected LogActivityService $logActivityService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = $this->logActivityService->getAllLogsByUser(Auth::guard(module_release_2_meta('snake').'_web')->user()->id);

        $data = [
            'title' => 'My Log Activity',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'My Log Activity',
                    'url' => '#',
                ],
            ],
            'logs' => $logs
        ];

        return view(module_release_2_meta('kebab').'::web.admin.log-activity.user.index', $data);
    }

}
