<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Auth;

use DesaDigitalSupport\RegionManager\Services\RegionService;
use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Auth\RegisterRequest;
use Modules\ModuleRelease2\Services\Auth\AuthenticationService;

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

        return view(module_release_2_meta('kebab').'::web.auth.register', $data);
    }

    /**
     * Handle the registration request.
     */
    public function store(RegisterRequest $request)
    {
        $request->validated();
        
        $this->authService->register($request);

        return redirect()->route(module_release_2_meta('kebab').'.user.index');
    }
}
