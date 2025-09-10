<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Admin;

use DesaDigitalSupport\RegionManager\Services\RegionService;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Shared\UpdateProfileRequest;
use Modules\ModuleRelease2\Services\Shared\ProfileService;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService,
        protected RegionService $regionService,
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
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Profile',
                    'url' => '#',
                ],
            ],
        ];
        
        return view(module_release_2_meta('kebab') . '::web.shared.profile.index', $data);
    }

    /**
     * Update the user's profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = module_release_2_auth_user();

        $this->profileService->updateProfile($user->id, $request->all());

        return redirect()->route(module_release_2_meta('kebab') . '.admin.profile.index')->with('success', 'Profile updated successfully.');
    }
}
