<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Auth\NewPasswordRequest;
use Modules\ModuleRelease2\Services\Auth\AuthenticationService;

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
        return view(module_release_2_meta('kebab').'::web.auth.reset-password', $data);
    }

    /**
     * Handle the new password request.
     */
    public function store(NewPasswordRequest $request)
    {
        $status = $this->authService->resetPassword($request->validated());

        return $status == Password::PASSWORD_RESET
                    ? view(module_release_2_meta('kebab').'::web.auth.reset-password-success', ['status' => __($status)])
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
