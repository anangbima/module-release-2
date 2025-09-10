<?php

namespace Modules\ModuleRelease2\Services\Admin;

use Modules\ModuleRelease2\Repositories\Interfaces\PermissionRepositoryInterface;
use Modules\ModuleRelease2\Services\Shared\LogActivityService;

class PermissionService
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository,
        protected LogActivityService $logActivityService,
    ){ }

    /**
     * Get all permissions.
     */
    public function getAllPermissions()
    {        
        return $this->permissionRepository->all();
    }

    /**
     * Get permission by ID.
     */
    public function getPermissionById(string $id)
    {
        return $this->permissionRepository->find($id);
    }

    /**
     * Create a new permission.
     */
    public function createPermission(array $data)
    {
        // Create the permission
        $permissionCreated = $this->permissionRepository->create($data);

        // Log the activity
        $this->logActivityService->log(
            action: 'create_permission',
            model: $permissionCreated,
            description: sprintf(
                'A new permission named "%s" has been successfully created.',
                $permissionCreated->name
            ),
            before: [],
            after: [
                'name' => $permissionCreated->name,
            ]
        );

        return $permissionCreated;
    }

    /**
     * Update an existing permission.
     */
    public function updatePermission(string $id, array $data)
    {
        $permission = $this->permissionRepository->find($id);

        // Update the permission
        $permissionUpdated = $this->permissionRepository->update($id, $data);

        // Log the activity
        $this->logActivityService->log(
            action: 'update_permission',
            model: $permissionUpdated,
            description: sprintf(
                'The permission "%s" has been updated successfully.',
                $permissionUpdated->name
            ),
            before: [
                'name' => $permission->name,
            ],
            after: [
                'name' => $permissionUpdated->name,
            ]
        );

        return $permissionUpdated;
    }

    /**
     * Delete a permission.
     */
    public function deletePermission(string $id)
    {
        $permission = $this->permissionRepository->find($id);

        // Delete the permission
        $permissionDeleted = $this->permissionRepository->delete($id);

        // Log the activity
        $this->logActivityService->log(
            action: 'delete_permission',
            model: $permission,
            description: sprintf(
                'The permission "%s" has been deleted from the system.',
                $permission->name
            ),
            before: [
                'name' => $permission->name,
            ],
            after: []
        );

        return $permissionDeleted;
    }
}