<?php

namespace App\PropertyManagement\Http\Controllers;

use App\PropertyManagement\Http\Requests\StoreTenantRequest;
use App\PropertyManagement\Models\Client;
use App\PropertyManagement\Services\Tenants\TenantService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct(
        private TenantService $tenantService
    ) {}

    /**
     * Display a listing of tenants
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $tenants = $this->tenantService->getAllTenants($perPage);

        return response()->json([
            'success' => true,
            'data' => $tenants,
        ]);
    }

    /**
     * Store a newly created tenant
     */
    public function store(StoreTenantRequest $request): JsonResponse
    {
        try {
            $tenant = $this->tenantService->createTenant($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tenant created successfully',
                'data' => $tenant,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified tenant
     */
    public function show(int $id): JsonResponse
    {
        try {
            $tenant = $this->tenantService->getTenant($id);

            return response()->json([
                'success' => true,
                'data' => $tenant,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified tenant
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $tenant = $this->tenantService->getTenant($id);
            $tenant = $this->tenantService->updateTenant($tenant, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Tenant updated successfully',
                'data' => $tenant,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get tenant account statement
     */
    public function accountStatement(Request $request, int $id): JsonResponse
    {
        try {
            $tenant = $this->tenantService->getTenant($id);
            
            $fromDate = $request->has('from_date') 
                ? Carbon::parse($request->input('from_date')) 
                : null;
            $toDate = $request->has('to_date') 
                ? Carbon::parse($request->input('to_date')) 
                : null;

            $statement = $this->tenantService->getAccountStatement($tenant, $fromDate, $toDate);

            return response()->json([
                'success' => true,
                'data' => $statement,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Search tenants
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q');
        
        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required',
            ], 400);
        }

        $tenants = $this->tenantService->searchTenants($query);

        return response()->json([
            'success' => true,
            'data' => $tenants,
        ]);
    }

    /**
     * Helper method to get tenant
     */
    private function getTenant(int $id): Client
    {
        return Client::findOrFail($id);
    }

    /**
     * Helper method to get all tenants
     */
    private function getAllTenants(int $perPage)
    {
        // This should use repository pagination
        return Client::paginate($perPage);
    }
}


