<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationService;

class VerifyEmailController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,    
    ) {}
    
    /**
     * Handle the email verification request.
     */
    public function __invoke(Request $request)
    {
        $this->authService->verifyEmail($request->route('user'));

        return redirect()->route(desa_module_template_meta('kebab').'.login');
    }
}
