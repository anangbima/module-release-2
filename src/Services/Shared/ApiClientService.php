<?php

namespace Modules\DesaModuleTemplate\Services\Shared;

use Modules\DesaModuleTemplate\Repositories\Interfaces\ApiClientRepositoryInterface;

class ApiClientService
{
    public function __construct(
        protected ApiClientRepositoryInterface $apiClientRepository,
        protected LogActivityService $logActivityService
    ){ }

    /**
     * Get an API client by its API key.
     */
    public function getApiClientByApiKey(string $apiKey)
    {
        return $this->apiClientRepository->findByApiKey($apiKey);
    }
}