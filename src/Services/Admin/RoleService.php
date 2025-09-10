<?php

namespace Modules\ModuleRelease2\Services\Admin;

use Illuminate\Support\Facades\Auth;
use Modules\ModuleRelease2\Repositories\Interfaces\RoleRepositoryInterface;
use Modules\ModuleRelease2\Services\Shared\LogActivityService;
use Modules\ModuleRelease2\Services\Shared\NotificationService;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
        protected LogActivityService $logActivityService,
        protected NotificationService $notificationService
    ){ }

    /**
     * Get all roles.
     */
    public function getAllRoles(...$relations)
    {
        return $this->roleRepository->all(...$relations);
    }

    /**
     * Get role by ID.
     */
    public function getRoleById(string $id, ...$relations)
    {
        return $this->roleRepository->find($id, ...$relations);
    }

    /**
     * Create a new role.
     */
    public function createRole(array $data)
    {
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $data['guard_name'] = module_release_2_meta('snake').'_web';

        // Create the role
        $roleCreated = $this->roleRepository->create($data);

        // Attach permissions to the role
        $roleCreated->permissions()->attach($permissions);

        // Log the activity
        $this->logActivityService->log(
            action: 'create_role',
            model: $roleCreated,
            description: sprintf(
                'A new role "%s" has been created with %d permission(s).',
                $roleCreated->name,
                $roleCreated->permissions->count()
            ),
            before: [],
            after: [
                'name' => $roleCreated->name,
                'permissions' => $roleCreated->permissions->pluck('name')->toArray(),
            ]
        );

        return $roleCreated;
    }

    /**
     * Update an existing role.
     */
    public function updateRole(string $id, array $data)
    {
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);
        
        $data['guard_name'] = module_release_2_meta('snake').'_web';

        $role = $this->roleRepository->find($id);

        // Update the role
        $roleUpdated = $this->roleRepository->update($id, $data);
        // Sync permissions with the role
        $roleUpdated->permissions()->sync($permissions);

        // Log the activity
        $this->logActivityService->log(
            action: 'update_role',
            model: $roleUpdated,
            description: sprintf(
                'The role "%s" has been updated. It now has %d permission(s).',
                $roleUpdated->name,
                $roleUpdated->permissions->count()
            ),
            before: [
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ],
            after: [
                'name' => $roleUpdated->name,
                'permissions' => $roleUpdated->permissions->pluck('name')->toArray(),
            ]
        );

        return $roleUpdated;
    }

    /**
     * Delete a role.
     */
    public function deleteRole(string $id)
    {
        $role = $this->roleRepository->find($id);

        // Delete the role
        $roleDeleted = $this->roleRepository->delete($id);

        // Log the activity
        $this->logActivityService->log(
            action: 'delete_role',
            model: $role,
            description: sprintf(
                'The role "%s" has been deleted along with its %d permission(s).',
                $role->name,
                $role->permissions->count()
            ),
            before: [
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ],
            after: []
        );

        return $roleDeleted;
    }
}