<?php

namespace Modules\ModuleRelease2\Repositories;

use Modules\ModuleRelease2\Models\ApiClient;
use Modules\ModuleRelease2\Repositories\Interfaces\ApiClientRepositoryInterface;

class ApiClientRepository implements ApiClientRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new ApiClient();
    }

    /**
     * Get all API clients.
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Find an API client by ID.
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Create a new API client.
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a apiClient by its ID.
     */
    public function update($id, array $data)
    {
        $user = $this->model->find($id);

        if (!$user) {
            return false;
        }

        $user->update($data);
        return $user;
    }

    /**
     * Delete an API client by ID.
     */
    public function delete(string $id)
    {
        $client = $this->find($id);
        
        if ($client) {
            return $client->delete();
        }
        
        return false;
    }

    /**
     * Find an API client by API key.
     */
    public function findByApiKey($apiKey)
    {
        return $this->model->where('api_key', $apiKey)->first();
    }
}