<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Api\Internal\Admin;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Services\Admin\UserService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUser();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

}
