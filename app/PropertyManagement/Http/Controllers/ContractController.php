<?php

namespace App\PropertyManagement\Http\Controllers;

use App\PropertyManagement\Http\Requests\StoreContractRequest;
use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Services\Contracts\ContractService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function __construct(
        private ContractService $contractService
    ) {}

    /**
     * Display a listing of contracts
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $contracts = $this->contractService->getAllContracts($perPage);

        return response()->json([
            'success' => true,
            'data' => $contracts,
        ]);
    }

    /**
     * Get all contracts (helper method)
     */
    private function getAllContracts(int $perPage)
    {
        // This should use repository pagination
        return $this->contractService->getActiveContracts();
    }

    /**
     * Get contract by ID (helper method)
     */
    private function getContract(int $id): Contract
    {
        // This should use repository
        return Contract::findOrFail($id);
    }

    /**
     * Store a newly created contract
     */
    public function store(StoreContractRequest $request): JsonResponse
    {
        try {
            $contractData = $request->validated();
            $representatives = $request->input('representatives', []);

            $contract = $this->contractService->createContract($contractData, $representatives);

            return response()->json([
                'success' => true,
                'message' => 'Contract created successfully',
                'data' => $contract,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified contract
     */
    public function show(int $id): JsonResponse
    {
        try {
            $contract = $this->contractService->getContract($id);

            return response()->json([
                'success' => true,
                'data' => $contract,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified contract
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $contract = $this->contractService->getContract($id);
            $contract = $this->contractService->updateContract($contract, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Contract updated successfully',
                'data' => $contract,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Terminate contract
     */
    public function terminate(Request $request, int $id): JsonResponse
    {
        try {
            $contract = $this->contractService->getContract($id);
            $terminationDate = \Carbon\Carbon::parse($request->input('termination_date'));
            $reason = $request->input('reason');

            $contract = $this->contractService->terminateContract($contract, $terminationDate, $reason);

            return response()->json([
                'success' => true,
                'message' => 'Contract terminated successfully',
                'data' => $contract,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get contract due amounts
     */
    public function dueAmounts(int $id): JsonResponse
    {
        try {
            $contract = $this->contractService->getContract($id);
            $amounts = $this->contractService->calculateDueAmounts($contract);

            return response()->json([
                'success' => true,
                'data' => $amounts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}

