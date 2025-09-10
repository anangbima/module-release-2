<?php

namespace Modules\ModuleRelease2\Http\Controllers\Api\Internal\Auth;

use Illuminate\Http\Request;
use Modules\TestModule1\Services\Shared\ApiResponseService;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Api\Auth\NewPasswordRequest;
use Modules\ModuleRelease2\Services\Auth\AuthenticationApiService;

class NewPasswordController extends Controller
{
    public function __construct(
        protected AuthenticationApiService $authService,
        protected ApiResponseService $apiResponseService
    ) {}


    /**
     * Store a newly created resource in storage.
     */
    public function store(NewPasswordRequest $request)
    {
        $result = $this->authService->resetPassword($request->validated());

        if ($result['status'] === 'error') {
            return $this->apiResponseService->error(
                $result['message'],
                $result['code'] ?? 400,
                $result['errors'] ?? null
            );
        }

        return $this->apiResponseService->success($result['data'],
            $result['message'] ?? 'Password reset successfully',
            $result['code'] ?? 200
        );
    }
}
