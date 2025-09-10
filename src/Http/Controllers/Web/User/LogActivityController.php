<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Models\LogActivity;
use Modules\DesaModuleTemplate\Services\User\LogActivityService;

class LogActivityController extends Controller
{
    public function __construct(
        protected LogActivityService $logActivityService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = $this->logActivityService->getAllLogsByUser(Auth::guard(desa_module_template_meta('snake').'_web')->user()->id);

        $data = [
            'logs' => $logs,
            'title' => 'Log Activity',
             'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.user.index'),
                ],
                [
                    'name' => 'Log Activity',
                    'url' => '#',
                ],
            ],
        ];

        return view(desa_module_template_meta('kebab').'::.web.user.log-activity.index', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(LogActivity $log)
    {
        $logActivity = $this->logActivityService->getLogById($log->id);

        $role = desa_module_template_auth_user()->role;

        $data = [
            'log' => $logActivity,
            'title' => 'Log Activity Detail',
            'role' => $role,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.user.index'),
                ],
                [
                    'name' => 'Log Activity',
                    'url' => route(desa_module_template_meta('kebab').'.user.logs.index'),
                ],
                [
                    'name' => 'Log Activity Detail',
                    'url' => '#',
                ],
            ],
        ];

        return view(desa_module_template_meta('kebab').'::web.shared.log-activity.show', $data);
    }   
}
