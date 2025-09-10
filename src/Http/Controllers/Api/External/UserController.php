<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Api\External;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Resources\Admin\UserResource;
use Modules\DesaModuleTemplate\Services\Admin\UserService;
use Modules\DesaModuleTemplate\Services\Shared\ApiResponseService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService, // temporary
        protected ApiResponseService $apiResponseService,
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUser();

        return $this->apiResponseService->success(UserResource::collection($users), 'Users retrieved successfully.');
    }

}
