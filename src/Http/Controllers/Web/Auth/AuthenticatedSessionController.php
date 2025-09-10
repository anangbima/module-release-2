<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ModuleRelease2\Helpers\ModuleMeta;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Auth\LoginRequest;
use Modules\ModuleRelease2\Services\Auth\AuthenticationService;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,
    ) {}
    
    /**
     * show the login form.
     */
    public function create()
    {
        $data = [
            'title' => 'Login',
        ];

        return view(module_release_2_meta('kebab').'::web.auth.login', $data);
    }

    /**
     * Handle an incoming login request.
     */
    public function store(LoginRequest $request)
    {
        $user = $this->authService->login($request);

        $otpConfig = config('auth.otp');

        if (($otpConfig['enabled'] ?? false) && in_array($user->role, $otpConfig['roles'] ?? [])) {

            $this->authService->sendOtp($user);

            return redirect()->route(module_release_2_meta('kebab').'.verify-otp')
                                    ->with('status', 'Please verify your account with OTP.');
        }

        if ($user->hasRole('user')) {
            return redirect()->route(module_release_2_meta('kebab').".user.index");
        }

        return redirect()->intended(module_release_2_meta('kebab').'/admin')->with('success', 'Login successful.');
    }

    /**
     * Handle an incoming logout request.
     */
    public function destroy()
    {
        $this->authService->logout(request());

        return redirect(module_release_2_meta('kebab')."/login");
    }
}
