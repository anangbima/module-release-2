<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationApiService;
use Modules\DesaModuleTemplate\Services\Shared\ApiResponseService;

class EmailVerificationPromptController extends Controller
{
    public function __construct(
        protected AuthenticationApiService $authService,
        protected ApiResponseService $apiResponseService
    ) {}

    /**
     * Show the email verification prompt.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user(desa_module_template_meta('snake').'_api');

        if ($user && $user->hasVerifiedEmail()) {
            return $this->apiResponseService->success(
                null,
                'Email already verified',
                200
            );
        }

        return $this->apiResponseService->error(
            'Email verification required',
            403
        );
    }
}
