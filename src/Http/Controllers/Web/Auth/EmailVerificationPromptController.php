<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Services\Auth\AuthenticationService;

class EmailVerificationPromptController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,    
    ) {}
    
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request)
    {
        return $request->user(module_release_2_meta('snake').'_web')?->hasVerifiedEmail()
            ? redirect()->intended()
            : view(module_release_2_meta('kebab').'::web.auth.verify-email');
    }
    
}
