<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\User;

use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Shared\UpdateProfileRequest;
use Modules\DesaModuleTemplate\Services\User\ProfileService;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = desa_module_template_auth_user()->role;

        $data = [
            'title' => 'Profile',
            'user' => desa_module_template_auth_user(),
            'role' => $role,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(desa_module_template_meta('kebab').'.user.index'),
                ],
                [
                    'name' => 'Profile',
                    'url' => '#',
                ],
            ],
        ];

        return view(desa_module_template_meta('kebab').'::web.shared.profile.index', $data);
    }

    /**
     * Display form for edit profile.
     */
    public function edit()
    {
        $data = [
            'title' => 'Edit Profile',
            'user' => desa_module_template_auth_user(),
        ];

        return view(desa_module_template_meta('kebab').'::web.user.profile.edit', $data);
    }

    /**
     * Update the user's profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = desa_module_template_auth_user();

        $this->profileService->updateProfile($user->id, $request->validated());

        return redirect()->route(desa_module_template_meta('kebab').'.user.profile.index')->with('success', 'Profile updated successfully.');
    }
}
