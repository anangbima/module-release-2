<?php

namespace Modules\ModuleRelease2\Http\Controllers\Api\Internal\Auth;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Services\Auth\AuthenticationApiService;
use Modules\ModuleRelease2\Services\Shared\ApiResponseService;

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
        $user = $request->user(module_release_2_meta('snake').'_api');

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
