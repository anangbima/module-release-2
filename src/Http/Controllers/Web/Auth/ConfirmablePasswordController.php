<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Auth\ConfirmablePasswordRequest;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationService;

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

        return view(desa_module_template_meta('kebab').'::web.auth.confirm-password', $data);
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
