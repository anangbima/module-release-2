<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\User;

use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Shared\UpdateProfileRequest;
use Modules\ModuleRelease2\Services\User\ProfileService;

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
        $role = module_release_2_auth_user()->role;

        $data = [
            'title' => 'Profile',
            'user' => module_release_2_auth_user(),
            'role' => $role,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.user.index'),
                ],
                [
                    'name' => 'Profile',
                    'url' => '#',
                ],
            ],
        ];

        return view(module_release_2_meta('kebab').'::web.shared.profile.index', $data);
    }

    /**
     * Display form for edit profile.
     */
    public function edit()
    {
        $data = [
            'title' => 'Edit Profile',
            'user' => module_release_2_auth_user(),
        ];

        return view(module_release_2_meta('kebab').'::web.user.profile.edit', $data);
    }

    /**
     * Update the user's profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = module_release_2_auth_user();

        $this->profileService->updateProfile($user->id, $request->validated());

        return redirect()->route(module_release_2_meta('kebab').'.user.profile.index')->with('success', 'Profile updated successfully.');
    }
}
