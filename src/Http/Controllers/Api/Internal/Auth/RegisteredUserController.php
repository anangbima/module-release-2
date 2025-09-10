<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Api\Auth\RegisterRequest;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationApiService;
use Modules\DesaModuleTemplate\Services\Shared\ApiResponseService;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected AuthenticationApiService $authService,
        protected ApiResponseService $apiResponseService
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        $request->validated();

        $result = $this->authService->register($request);

        if ($result['status'] === 'error') {
            return $this->apiResponseService->error(
                $result['message'],
                $result['code'] ?? 400,
                $result['errors'] ?? null
            );
        }

        return $this->apiResponseService->success($result['data'], 
            $result['message'] ?? 'Registration successful', 
            $result['code'] ?? 200
        );
    }
}
