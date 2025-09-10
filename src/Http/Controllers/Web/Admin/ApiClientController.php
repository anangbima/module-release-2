<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Http\Requests\Web\Admin\StoreApiClientRequest;
use Modules\DesaModuleTemplate\Models\ApiClient;
use Modules\DesaModuleTemplate\Services\Admin\ApiClientService;

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
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Api Clients',
                    'url' => '#',
                ],
            ],
            'apiClients' => $apiClients,
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.api-client.index', $data);
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
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Api Clients',
                    'url' => route(desa_module_template_meta('kebab').'.admin.api-clients.index'),
                ],
                 [
                    'name' => 'Api New API Clients',
                    'url' => '#',
                ],
            ],
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.api-client.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApiClientRequest $request)
    {
        $this->apiClientService->createApiClient($request->validated());

        return redirect()->route(desa_module_template_meta('kebab').'.admin.api-clients.index')
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
                    'url' => route(desa_module_template_meta('kebab').'.admin.index'),
                ],
                [
                    'name' => 'Api Clients',
                    'url' => route(desa_module_template_meta('kebab').'.admin.api-clients.index'),
                ],
                [
                    'name' => $apiClient->name,
                    'url' => '#',
                ],
            ],
        ];

        return view(desa_module_template_meta('kebab').'::web.admin.api-client.show', $data);
    }

    /**
     * Delete the specified resource from storage.
     */
    public function destroy(ApiClient $apiClient)
    {
        $this->apiClientService->deleteApiClient($apiClient->id);

        return redirect()->route(desa_module_template_meta('kebab').'.admin.api-clients.index')
            ->with('success', 'API Client deleted successfully.');
    }
}
