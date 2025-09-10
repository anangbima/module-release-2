<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Auth\ConfirmablePasswordRequest;
use Modules\ModuleRelease2\Services\Auth\AuthenticationService;

class ConfirmablePasswordController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,    
    ) {}

    /**
     * Display the confirmable password form.
     */
    public function create()
    {
        $data = [
            'title' => 'Confirm Password',
        ];

        return view(module_release_2_meta('kebab').'::web.auth.confirm-password', $data);
    }

    /**
     * Handle the confirmable password request.
     */
    public function store(ConfirmablePasswordRequest $request)
    {
        $request->validated();
        $this->authService->confirmPassword($request->password);

        return redirect()->intended();
    }
    
}
