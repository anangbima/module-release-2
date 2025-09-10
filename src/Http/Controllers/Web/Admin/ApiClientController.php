<?php

namespace Modules\ModuleRelease2\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\ModuleRelease2\Http\Controllers\Controller;
use Modules\ModuleRelease2\Http\Requests\Web\Admin\StoreApiClientRequest;
use Modules\ModuleRelease2\Models\ApiClient;
use Modules\ModuleRelease2\Services\Admin\ApiClientService;

class ApiClientController extends Controller
{
    public function __construct(
        protected ApiClientService $apiClientService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiClients = $this->apiClientService->getAllApiClients();

        $data = [
            'title' => 'API Clients',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Api Clients',
                    'url' => '#',
                ],
            ],
            'apiClients' => $apiClients,
        ];

        return view(module_release_2_meta('kebab').'::web.admin.api-client.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Add New API Client',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Api Clients',
                    'url' => route(module_release_2_meta('kebab').'.admin.api-clients.index'),
                ],
                 [
                    'name' => 'Api New API Clients',
                    'url' => '#',
                ],
            ],
        ];

        return view(module_release_2_meta('kebab').'::web.admin.api-client.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApiClientRequest $request)
    {
        $this->apiClientService->createApiClient($request->validated());

        return redirect()->route(module_release_2_meta('kebab').'.admin.api-clients.index')
            ->with('success', 'API Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ApiClient $apiClient)
    {
        $apiClient = $this->apiClientService->getApiClientById($apiClient->id);

        $data = [
            'title' => 'View API Client',
            'apiClient' => $apiClient,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'url' => route(module_release_2_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Api Clients',
                    'url' => route(module_release_2_meta('kebab').'.admin.api-clients.index'),
                ],
                [
                    'name' => $apiClient->name,
                    'url' => '#',
                ],
            ],
        ];

        return view(module_release_2_meta('kebab').'::web.admin.api-client.show', $data);
    }

    /**
     * Delete the specified resource from storage.
     */
    public function destroy(ApiClient $apiClient)
    {
        $this->apiClientService->deleteApiClient($apiClient->id);

        return redirect()->route(module_release_2_meta('kebab').'.admin.api-clients.index')
            ->with('success', 'API Client deleted successfully.');
    }
}
