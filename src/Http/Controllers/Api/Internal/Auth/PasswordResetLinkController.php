<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Api\Auth\PasswordResetLinkRequest;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationApiService;
use Modules\DesaModuleTemplate\Services\Shared\ApiResponseService;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        protected AuthenticationApiService $authService,
        protected ApiResponseService $apiResponseService
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(PasswordResetLinkRequest $request)
    {
        $request->validated();

        $result = $this->authService->sendPasswordResetLink($request->email);

        if ($result['status'] === 'error') {
            return $this->apiResponseService->error(
                $result['message'],
                $result['code'] ?? 400,
                $result['errors'] ?? null
            );
        }

        return $this->apiResponseService->success($result['data'],
            $result['message'] ?? 'Password reset link sent successfully',
            $result['code'] ?? 200
        );
    }
}
