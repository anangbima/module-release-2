<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct(
        // protected someService $someService,
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'User Dashboard',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.user.index'),
                ],
                [
                    'name' => 'Home',
                    'url' => '#',
                ],
            ],
        ];

        return view(desa_module_template_meta('kebab').'::web.user.home.index', $data);
    }
}
