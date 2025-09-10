<?php

namespace Modules\DesaModuleTemplate\Services\Shared;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\DesaModuleTemplate\Repositories\Interfaces\UserRepositoryInterface;

class ProfileService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected LogActivityService $logActivityService,
        protected MediaService $mediaService,
    ){ }

    /**
     * Update user profile information.
     */
    public function updateProfile(string $id, array $data)
    {
        $user = $this->userRepository->find($id);

        $newData = [
            'name' => $data['name'],
            'province_code' => $data['province'] ?? null,
            'city_code' => $data['city'] ?? null,
            'district_code' => $data['district'] ?? null,
            'village_code' => $data['village'] ?? null,
        ];

        // Simpan state sebelum diupdate untuk log
        $before = [
            'name' => $user->name,
            'province_code' => $user->province_code,
            'city_code' => $user->city_code,
            'district_code' => $user->district_code,
            'village_code' => $user->village_code,
        ];

        // Update user details
        $userUpdated = $this->userRepository->update($id, $newData);

        // Simpan state setelah update untuk log
        $after = [
            'name' => $userUpdated->name,
            'province_code' => $userUpdated->province_code,
            'city_code' => $userUpdated->city_code,
            'district_code' => $userUpdated->district_code,
            'village_code' => $userUpdated->village_code,
        ];

        // Log the activity dengan deskripsi yang enak dibaca di UI
        $this->logActivityService->log(
            action: 'update_profile',
            model: $userUpdated,
            description: sprintf(
                "User profile for \"%s\" has been successfully updated. Updated details:\n- Name: %s\n- Province: %s\n- City: %s\n- District: %s\n- Village: %s",
                $userUpdated->name,
                $userUpdated->name,
                $userUpdated->province_code ?? '-',
                $userUpdated->city_code ?? '-',
                $userUpdated->district_code ?? '-',
                $userUpdated->village_code ?? '-'
            ),
            before: $before,
            after: $after
        );

        return $userUpdated;
    }

    /**
     * Update user profile image.
     */
    public function updateProfileImage(UploadedFile $image)
    {
        $user = Auth::guard(desa_module_template_meta('snake').'_web')->user();

        $existingMedia = $user->getSingleMedia('profile_image');

        if ($existingMedia) {
            $this->mediaService->remove($existingMedia);
        }

        // Upload the new image and associate it with the user
        $media = $this->mediaService->upload(
            file: $image,
            model: $user,
            usage: 'profile_image',
            collection: 'default',
            disk: 'public'
        );

        // Simpan state sebelum dan sesudah untuk log
        $before = ['image' => $existingMedia ? $existingMedia->file_name : 'No previous image'];
        $after = ['image' => $image->hashName()];

        // Log the activity dengan deskripsi yang lebih enak dibaca
        $this->logActivityService->log(
            action: 'update_profile_image',
            model: $user,
            description: sprintf(
                "Profile image for user \"%s\" has been updated.\n- Previous image: %s\n- New image: %s",
                $user->name,
                $before['image'],
                $after['image']
            ),
            before: $before,
            after: $after
        );

        return $media;
    }

    /**
     * Remove user profile image.
     */
    public function removeProfileImage()
    {
        $user = Auth::guard(desa_module_template_meta('snake').'_web')->user();

        $existingMedia = $user->getSingleMedia('profile_image');

        if ($existingMedia) {
            $this->mediaService->remove($existingMedia);

            // Simpan state sebelum dihapus untuk log
            $before = ['image' => $existingMedia->file_name];

            // Log the activity dengan deskripsi lebih jelas
            $this->logActivityService->log(
                action: 'remove_profile_image',
                model: $user,
                description: sprintf(
                    "Profile image for user \"%s\" has been removed.\n- Removed image: %s",
                    $user->name,
                    $before['image']
                ),
                before: $before,
                after: []
            );

            return true;
        }

        return false;
    }
}