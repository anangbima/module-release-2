<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Auth\PasswordResetLinkRequest;
use Modules\ModuleRelease2\Services\Auth\AuthenticationService;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,    
    ) {}
    
    /**
     * Display the password reset link request form.
     */
    public function create()
    {
        $data = [
            'title' => 'Forgot Password',
        ];

        return view(module_release_2_meta('kebab').'::web.auth.forgot-password', $data);
    }

    /**
     * Handle the password reset link request.
     */
    public function store(PasswordResetLinkRequest $request)
    {
        $request->validated();

        $status = $this->authService->sendPasswordResetLink($request->email);

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
