<?php

namespace Modules\DesaModuleTemplate\Services\Admin;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\DesaModuleTemplate\Repositories\Interfaces\UserRepositoryInterface;
use Modules\DesaModuleTemplate\Services\Shared\ExportService;
use Modules\DesaModuleTemplate\Services\Shared\LogActivityService;
use Modules\DesaModuleTemplate\Services\Shared\NotificationService;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected LogActivityService $logActivityService,
        protected NotificationService $notificationService,
        protected ExportService $exportService,
    ){ }

    /**
     * Get all users with mapped data. for exporting or displaying.
     */
    public function getMappedUsers(): Collection
    {
        return $this->userRepository->all()->map(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'province' => $user->province_name ?? '-',
            'city' => $user->city_name ?? '-',
            'district' => $user->district_name ?? '-',
            'village' => $user->village_name ?? '-',
        ]);
    }

    /**
     * Get all users.
     */
    public function getAllUser(...$relations)
    {
        return $this->userRepository->all(...$relations);
    }

    /**
     * Get user by ID.
     */
    public function getUserById(string $id, ...$relations)
    {
        return $this->userRepository->find($id, ...$relations);
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data)
    {
        // Set default values for certain fields
        $data['email_verified_at'] = now();

        // Map province, city, district, and village codes to their respective names
        $data['province_code'] = $data['province'] ?? null;
        $data['city_code'] = $data['city'] ?? null;
        $data['district_code'] = $data['district'] ?? null;
        $data['village_code'] = $data['village'] ?? null;
        
        // Ensure role is set, default to 'user' if not provided
        $role = $data['role'];
        unset($data['role']);

        // Ensure password is hashed
        $data['password'] = bcrypt($data['password']);

        // Create the user
        $userCreated = $this->userRepository->create($data);

        // Assign role to the user
        $userCreated->assignRole($role);

        // Log the activity
        $this->logActivityService->log(
            action: 'create_user',
            model: $userCreated,
            description: sprintf(
                'A new user account has been created for "%s" with the email "%s". The role "%s" has been assigned.',
                $userCreated->name,
                $userCreated->email,
                $role
            ),
            before: [],
            after: [
                'name' => $userCreated->name,
                'email' => $userCreated->email,
                'role' => $role,
            ]
        );

        return $userCreated;
    }

    /**
     * Update an existing user.
     */
    public function updateUser(string $id, array $data)
    {
        // Get user by ID
        $user = $this->userRepository->find($id);

        // Ensure role is set, default to 'user' if not provided
        $role = $data['role'] ?? 'user';
        unset($data['role']);

        // Map province, city, district, and village codes to their respective names
        $data['province_code'] = $data['province'] ?? null;
        $data['city_code'] = $data['city'] ?? null;
        $data['district_code'] = $data['district'] ?? null;
        $data['village_code'] = $data['village'] ?? null;


        // Update the user data
        $userUpdated = $this->userRepository->update($user->id, $data);
        
        // Assign role to the user
        $userUpdated->syncRoles($role);

        // Log the activity
        $this->logActivityService->log(
            action: 'update_user',
            model: $userUpdated,
            description: sprintf(
                'User account for "%s" has been updated. Previous details: [Name: %s, Email: %s, Role: %s]. Updated details: [Name: %s, Email: %s, Role: %s].',
                $userUpdated->name,
                $user->name,
                $user->email,
                $user->roles->pluck('name')->implode(', ') ?: 'none',
                $userUpdated->name,
                $userUpdated->email,
                $role
            ),
            before: [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')->implode(', ') ?: 'none',
            ],
            after: [
                'name' => $userUpdated->name,
                'email' => $userUpdated->email,
                'role' => $role,
            ]
        );

        return $userUpdated;
    }

    /**
     * Delete a user.
     */
    public function deleteUser(string $id)
    {
        // Get user by ID
        $user = $this->userRepository->find($id);

        $userDeleted = $this->userRepository->delete($user->id);

        // Log the activity
        $this->logActivityService->log(
            action: 'delete_user',
            model: $user,
            description: sprintf(
                'The user account "%s" (Email: %s, Role: %s) has been permanently deleted from the system.',
                $user->name,
                $user->email,
                $user->roles->pluck('name')->implode(', ') ?: 'none'
            ),
            before: [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')->implode(', ') ?: 'none',
            ],
            after: []
        );

        return $userDeleted;
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus(string $id, bool $status)
    {
        $user = $this->userRepository->find($id);

        $userUpdated = $this->userRepository->update($user->id, ['status' => $status ? 'active' : 'inactive']);

        // Log the activity
        $this->logActivityService->log(
            action: 'toggle_user_status',
            model: $userUpdated,
            description: sprintf(
                'The user account "%s" has been %s.',
                $user->name,
                $status ? 'activated' : 'deactivated'
            ),
            before: ['status' => $user->status],
            after: ['status' => $userUpdated->status]
        );

        return $userUpdated;
    }

    /**
     * Check if a user exists by email.
     */
    public function existsByEmail(string $email): bool
    {
        return $this->userRepository->checkByEmail($email);
    }

    /**
     * Count active users
     */
    public function countActive(): int
    {
        return $this->userRepository->countByStatus('active');
    }

    // Count inactive user
    public function countInactive(): int
    {
        return $this->userRepository->countByStatus('inactive');
    }

    /**
     * Create user from import data.
     */
    public function createFromImport(array $data)
    {
        // Ensure role is set, default to 'user' if not provided
        $role = $data['role'] ?? 'user';
        unset($data['role']);

        // Set default values for certain fields
        $data['email_verified_at'] = now();

        // Ensure password is hashed
        $data['password'] = bcrypt($data['password']);

        // Create the user
        $userCreated = $this->userRepository->create($data);

        // Assign role to the user
        $userCreated->assignRole($role);

        return $userCreated;
    }
}