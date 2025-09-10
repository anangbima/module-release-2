<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Models\User;
use Modules\DesaModuleTemplate\Services\Admin\UserService;

class UserStatusController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {} 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Toggle user status
        $status = $request->boolean('status');
        $this->userService->toggleStatus($user->id, $status);

       return redirect()->route(desa_module_template_meta('kebab').'.admin.users.index')->with('success', 'User status updated successfully.');
    }
}
