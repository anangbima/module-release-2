<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Guest;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Models\Permission;
use Modules\DesaModuleTemplate\Services\Shared\PermissionRegistrar;

class HomeController extends Controller
{   
    /**
     * Display a listing of the resource.
     */
    public function index(PermissionRegistrar $registrar)
    {   
        $data = [
            'title' => 'Dashboard',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.index'),
                ],
                [
                    'name' => 'Home',
                    'url' => '#',
                ],
            ],
        ];

        return view(desa_module_template_meta('kebab').'::web.guest.home.index', $data);
    }
}
