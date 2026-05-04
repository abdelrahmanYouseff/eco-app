<?php

namespace App\PropertyManagement\Http\Controllers;

use App\PropertyManagement\Http\Requests\StoreUnitRequest;
use App\PropertyManagement\Models\Unit;
use App\PropertyManagement\Services\Buildings\BuildingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct(
        private BuildingService $buildingService
    ) {}

    /**
     * Display a listing of units
     */
    public function index(Request $request): JsonResponse
    {
        $buildingId = $request->input('building_id');
        $units = $buildingId 
            ? $this->buildingService->getUnitsByBuilding($buildingId)
            : $this->buildingService->getAllUnits();

        return response()->json([
            'success' => true,
            'data' => $units,
        ]);
    }

    /**
     * Store a newly created unit
     */
    public function store(StoreUnitRequest $request): JsonResponse
    {
        try {
            $unit = $this->buildingService->createUnit($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Unit created successfully',
                'data' => $unit,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified unit
     */
    public function show(int $id): JsonResponse
    {
        try {
            $unit = $this->buildingService->getUnitDetails($id);

            return response()->json([
                'success' => true,
                'data' => $unit,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified unit
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit = $this->buildingService->updateUnit($unit, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Unit updated successfully',
                'data' => $unit,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get available units
     */
    public function available(Request $request): JsonResponse
    {
        $buildingId = $request->input('building_id');
        $units = $this->buildingService->getAvailableUnits($buildingId);

        return response()->json([
            'success' => true,
            'data' => $units,
        ]);
    }

    /**
     * Get occupied units
     */
    public function occupied(Request $request): JsonResponse
    {
        $buildingId = $request->input('building_id');
        $units = $this->buildingService->getOccupiedUnits($buildingId);

        return response()->json([
            'success' => true,
            'data' => $units,
        ]);
    }

    /**
     * Helper method to get all units
     */
    private function getAllUnits()
    {
        // This should use repository
        return Unit::with(['building', 'contracts'])->get();
    }
}


