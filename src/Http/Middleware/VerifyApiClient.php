<?php

namespace Modules\DesaModuleTemplate\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\DesaModuleTemplate\Models\ApiClient;
use Modules\DesaModuleTemplate\Services\Shared\ApiClientService;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiClient
{
    public function __construct(
        protected ApiClientService $apiClientService
    ) { }

    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request has the necessary headers
        $apiKey = $request->header('X-API-Key');
        $secretKey = $request->header('X-Secret-Key');

        
        // Validate API key and secret key
        if (!$apiKey || !$secretKey) {
            return response()->json(['error' => 'Missing credentials'], 401);
        }
        
        // Fetch the API client from the database
        $client = $this->apiClientService->getApiClientByApiKey($apiKey);
        
        Log::info('API Key:', ['input' => $apiKey, 'stored' => $client?->api_key]);
        Log::info('Secret Key:', [
            'input' => hash('sha256', $secretKey),
            'stored' => $client?->secret_key
        ]);

        // Check if the client exists and the secret key matches
        if (!$client || !hash_equals($client->secret_key, $secretKey)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Check if the client is active
        if (!$client->is_active) {
            return response()->json(['error' => 'Client is inactive'], 403);
        }

        // Attach the client to the request for further processing
        $request->merge([
            'api_client' => $client,
        ]);

        return $next($request);
    }
}
