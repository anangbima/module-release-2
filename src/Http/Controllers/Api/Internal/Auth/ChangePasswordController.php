<?php

namespace Modules\ModuleRelease2\Http\Controllers\Api\Internal\Auth;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Api\Auth\ChangePasswordRequest;
use Modules\ModuleRelease2\Services\Auth\AuthenticationApiService;
use Modules\ModuleRelease2\Services\Shared\ApiResponseService;

class ChangePasswordController extends Controller
{
    public function __construct(
        protected AuthenticationApiService $authService,
        protected ApiResponseService $apiResponseService
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChangePasswordRequest $request)
    {
        $result = $this->authService->changePassword($request->validated());

        if ($result['status'] === 'error') {
            return $this->apiResponseService->error(
                $result['message'],
                $result['code'] ?? 400,
                $result['errors'] ?? null
            );
        }

        return $this->apiResponseService->success($result['data'], 
            $result['message'] ?? 'Password changed successfully', 
            $result['code'] ?? 200
        );
    }
}
