<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationService;

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
        return $request->user(desa_module_template_meta('snake').'_web')?->hasVerifiedEmail()
            ? redirect()->intended()
            : view(desa_module_template_meta('kebab').'::web.auth.verify-email');
    }
    
}
