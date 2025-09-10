<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Auth\ChangePasswordRequest;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationService;

class ChangePasswordController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,
    ) {}

    /**
     * Display the change password form.
     */
    public function index(Request $request)
    {
        $data = [
            'title' => 'Change Password',
        ];

        return view(desa_module_template_meta('kebab').'::web.auth.change-password', $data);
    }
    
    /**
     * Handle the change password request.
     */
    public function store(ChangePasswordRequest $request)
    {
        $this->authService->changePassword($request->validated());

        return redirect()->route(desa_module_template_meta('kebab').'.login')->with('success', 'Password changed successfully.');
    }
}
