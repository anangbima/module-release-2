<?php

namespace Modules\ModuleRelease2\Http\Controllers\Api\External;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Resources\Admin\UserResource;
use Modules\ModuleRelease2\Services\Admin\UserService;
use Modules\ModuleRelease2\Services\Shared\ApiResponseService;

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
