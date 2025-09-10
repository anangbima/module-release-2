<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Shared\UpdateProfileImageRequest;
use Modules\ModuleRelease2\Services\Admin\ProfileService;

class ProfileImageController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileImageRequest $request)
    {
        $this->profileService->updateProfileImage($request->file('image'));

        return redirect()->route(module_release_2_meta('kebab').'.admin.profile.index')
            ->with('success', 'Profile image updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $this->profileService->removeProfileImage();

        return redirect()->route(module_release_2_meta('kebab').'.admin.profile.index')
            ->with('success', 'Profile image removed successfully.');
    }
}
