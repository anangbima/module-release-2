<?php

namespace Modules\ModuleRelease2\Http\Controllers\Api\Internal\Auth;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Api\Auth\ConfirmablePasswordRequest;
use Modules\ModuleRelease2\Services\Auth\AuthenticationApiService;
use Modules\ModuleRelease2\Services\Shared\ApiResponseService;

class ConfirmablePasswordController extends Controller
{
    public function __construct(
        protected AuthenticationApiService $authService,
        protected ApiResponseService $apiResponseService
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConfirmablePasswordRequest $request)
    {
        $request->validated();
        $result = $this->authService->confirmPassword($request->input('password'));

        if ($result['status'] === 'error') {
            return $this->apiResponseService->error(
                $result['message'],
                $result['code'] ?? 400,
                $result['errors'] ?? null
            );
        }

        return $this->apiResponseService->success($result['data'], 
            $result['message'] ?? 'Password confirmed successfully', 
            $result['code'] ?? 200
        );
    }
}
