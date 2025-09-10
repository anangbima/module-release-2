<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Auth;

use DesaDigitalSupport\RegionManager\Services\RegionService;
use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Auth\RegisterRequest;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationService;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,    
        protected RegionService $regionService
    ) {}
    
    /**
     * Display the registration form.
     */
    public function create()
    {
        $data = [
            'title' => 'Register',
        ];

        return view(desa_module_template_meta('kebab').'::web.auth.register', $data);
    }

    /**
     * Handle the registration request.
     */
    public function store(RegisterRequest $request)
    {
        $request->validated();
        
        $this->authService->register($request);

        return redirect()->route(desa_module_template_meta('kebab').'.user.index');
    }
}
