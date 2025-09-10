<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\DesaModuleTemplate\Helpers\ModuleMeta;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Auth\LoginRequest;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationService;

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

        return view(desa_module_template_meta('kebab').'::web.auth.login', $data);
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

            return redirect()->route(desa_module_template_meta('kebab').'.verify-otp')
                                    ->with('status', 'Please verify your account with OTP.');
        }

        if ($user->hasRole('user')) {
            return redirect()->route(desa_module_template_meta('kebab').".user.index");
        }

        return redirect()->intended(desa_module_template_meta('kebab').'/admin')->with('success', 'Login successful.');
    }

    /**
     * Handle an incoming logout request.
     */
    public function destroy()
    {
        $this->authService->logout(request());

        return redirect(desa_module_template_meta('kebab')."/login");
    }
}
