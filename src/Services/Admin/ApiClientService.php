<?php

namespace Modules\ModuleRelease2\Services\Admin;

use Modules\ModuleRelease2\Services\Shared\ApiClientService as SharedApiClientService;
use Illuminate\Support\Str;

class ApiClientService extends SharedApiClientService
{
    /**
     * Get all API clients.
     */ 
    public function getAllApiClients()
    {
        return $this->apiClientRepository->all();
    }

    /**
     * Get an API client by ID.
     */
    public function getApiClientById(string $id)
    {
        return $this->apiClientRepository->find($id);
    }

    /**
     * Create a new API client.
     */
    public function createApiClient(array $data)
    {
        $data['api_key'] = Str::random(32);
        $data['secret_key'] = Str::random(64);

        $apiClientCreated = $this->apiClientRepository->create($data);

        // Log the activity
        $this->logActivityService->log(
            action: 'create_api_client',
            model: $apiClientCreated,
            description: sprintf(
                "A new API Client named \"%s\" has been successfully created and is now available for use.",
                $apiClientCreated->name
            ),
            before: [],
            after: [
                'name' => $apiClientCreated->name,
            ]
        );

        return $apiClientCreated;
    }
    
    /**
     * Delete an API client by ID.
     */
    public function deleteApiClient(string $id)
    {
        $apiClient = $this->apiClientRepository->find($id);

        $apiClientDeleted = $this->apiClientRepository->delete($id);

        // Log the activity
        $this->logActivityService->log(
            action: 'delete_api_client',
            model: $apiClient,
            description: sprintf(
                "The API Client \"%s\" has been deleted and is no longer available for system access.",
                $apiClient->name
            ),
            before: [
                'name' => $apiClient->name,
            ],
            after: []
        );

        return $apiClientDeleted;
    }

    /**
     * Toggle status.
     */
    public function toggleStatus(string $id, bool|string $status)
    {
        // Cast status ke boolean
        $status = (bool) $status;

        $apiClient = $this->apiClientRepository->find($id);

        $apiClientUpdated = $this->apiClientRepository->update($apiClient->id, ['is_active' => $status]);

        // Log activity
        $this->logActivityService->log(
            action: 'toggle_api_client_status',
            model: $apiClientUpdated,
            description: sprintf(
                'The API Client "%s" status has been changed from %s to %s.',
                $apiClient->name,
                $apiClient->is_active ? 'Active' : 'Inactive',
                $apiClientUpdated->is_active ? 'Active' : 'Inactive'
            ),
            before: [
                'status' => $apiClient->is_active
            ],
            after: [
                'status' => $apiClientUpdated->is_active
            ]
        );

        return $apiClientUpdated;
    }

}