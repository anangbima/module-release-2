<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Services\Auth\AuthenticationService;

class EmailVerificationNotificationController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService,    
    ) {}
    
    /**
     * Handle the email verification notification request.
     */
    public function store(Request $request)
    {
        $this->authService->resendEmailVerification($request);

        // Optionally, you can redirect or return a response
        return back()->with('status', 'verification-link-sent');
    }
}
