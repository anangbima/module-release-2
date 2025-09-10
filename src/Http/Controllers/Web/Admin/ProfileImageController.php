<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Shared\UpdateProfileImageRequest;
use Modules\DesaModuleTemplate\Services\Admin\ProfileService;

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

        return redirect()->route(desa_module_template_meta('kebab').'.admin.profile.index')
            ->with('success', 'Profile image updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $this->profileService->removeProfileImage();

        return redirect()->route(desa_module_template_meta('kebab').'.admin.profile.index')
            ->with('success', 'Profile image removed successfully.');
    }
}
