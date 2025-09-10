<?php

namespace Modules\ModuleRelease2\Http\Controllers\Api\Internal\Auth;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Services\Auth\AuthenticationApiService;
use Modules\ModuleRelease2\Services\Shared\ApiResponseService;

class VerifyEmailController extends Controller
{
    public function __construct(
        protected AuthenticationApiService $authService,
        protected ApiResponseService $apiResponseService
    ) {}
    
    public function __invoke(Request $request)
    {
        $result = $this->authService->verifyEmail($request->route('user'));

        if ($result['status'] === 'error') {
            return $this->apiResponseService->error(
                $result['message'],
                $result['code'] ?? 400,
                $result['errors'] ?? null
            );
        }

        return $this->apiResponseService->success($result['data'],
            $result['message'] ?? 'Email verified successfully',
            $result['code'] ?? 200
        );
    }
}
