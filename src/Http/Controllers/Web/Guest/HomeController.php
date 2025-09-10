<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Guest;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Models\Permission;
use Modules\ModuleRelease2\Services\Shared\PermissionRegistrar;

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
                    'url' => route(module_release_2_meta('kebab').'.index'),
                ],
                [
                    'name' => 'Home',
                    'url' => '#',
                ],
            ],
        ];

        return view(module_release_2_meta('kebab').'::web.guest.home.index', $data);
    }
}
