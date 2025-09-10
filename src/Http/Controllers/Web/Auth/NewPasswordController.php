<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Auth\NewPasswordRequest;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationService;

class NewPasswordController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,    
    ) {}
    
    /**
     * Display the new password form.
     */
    public function create(Request $request)
    {
        $data = [
            'title' => 'Set New Password',
            'request' => $request,
        ];
        // This method can be used to show a view for setting a new password.
        return view(desa_module_template_meta('kebab').'::web.auth.reset-password', $data);
    }

    /**
     * Handle the new password request.
     */
    public function store(NewPasswordRequest $request)
    {
        $status = $this->authService->resetPassword($request->validated());

        return $status == Password::PASSWORD_RESET
                    ? view(desa_module_template_meta('kebab').'::web.auth.reset-password-success', ['status' => __($status)])
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
