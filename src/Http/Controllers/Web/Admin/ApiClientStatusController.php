<?php

namespace Modules\DesaModuleTemplate\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Modules\DesaModuleTemplate\Http\Controllers\Controller;
use Modules\DesaModuleTemplate\Models\ApiClient;
use Modules\DesaModuleTemplate\Services\Admin\ApiClientService;

class ApiClientStatusController extends Controller
{
    public function __construct(
        protected ApiClientService $apiClientService
    ) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApiClient $apiClient)
    {
        // Toggle Api Client status
        $status = $request->boolean('status');
        $this->apiClientService->toggleStatus($apiClient->id, $status);

       return redirect()->route(desa_module_template_meta('kebab').'.admin.api-clients.index')->with('success', 'Api Client status updated successfully.');
    }
}
