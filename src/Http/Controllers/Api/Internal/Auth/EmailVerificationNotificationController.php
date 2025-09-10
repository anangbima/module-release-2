<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Auth;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Services\Auth\AuthenticationApiService;
use Modules\DesaModuleTemplate\Services\Shared\ApiResponseService;

class EmailVerificationNotificationController extends Controller
{
    public function __construct(
        protected AuthenticationApiService $authService,
        protected ApiResponseService $apiResponseService
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->authService->resendEmailVerification($request);

        if ($result['status'] === 'error') {
            return $this->apiResponseService->error(
                $result['message'],
                $result['code'] ?? 400,
                $result['errors'] ?? null
            );
        }

        return $this->apiResponseService->success($result['data'], 
            $result['message'] ?? 'Email verification notification sent successfully', 
            $result['code'] ?? 200
        );
    }
}
