<?php

namespace App\PropertyManagement\Services\Buildings;

use App\PropertyManagement\Models\Unit;
use App\PropertyManagement\Repositories\Buildings\Interfaces\BuildingRepositoryInterface;
use Illuminate\Support\Collection;

class BuildingService
{
    public function __construct(
        private BuildingRepositoryInterface $buildingRepository
    ) {}

    /**
     * Create a new unit
     */
    public function createUnit(array $data): Unit
    {
        // Validate unit number uniqueness within building
        $existingUnit = Unit::where('building_id', $data['building_id'])
            ->where('unit_number', $data['unit_number'])
            ->first();

        if ($existingUnit) {
            throw new \Exception('Unit number already exists in this building');
        }

        return $this->buildingRepository->createUnit($data);
    }

    /**
     * Update unit
     */
    public function updateUnit(Unit $unit, array $data): Unit
    {
        // Validate unit number uniqueness if being changed
        if (isset($data['unit_number']) && $data['unit_number'] !== $unit->unit_number) {
            $existingUnit = Unit::where('building_id', $unit->building_id)
                ->where('unit_number', $data['unit_number'])
                ->where('id', '!=', $unit->id)
                ->first();

            if ($existingUnit) {
                throw new \Exception('Unit number already exists in this building');
            }
        }

        return $this->buildingRepository->updateUnit($unit, $data);
    }

    /**
     * Delete unit
     */
    public function deleteUnit(Unit $unit): bool
    {
        // Check if unit has active contracts
        $activeContracts = $unit->contracts()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();

        if ($activeContracts > 0) {
            throw new \Exception('Cannot delete unit with active contracts');
        }

        return $this->buildingRepository->deleteUnit($unit);
    }

    /**
     * Get available units
     */
    public function getAvailableUnits(?int $buildingId = null): Collection
    {
        return $this->buildingRepository->getAvailableUnits($buildingId);
    }

    /**
     * Get occupied units
     */
    public function getOccupiedUnits(?int $buildingId = null): Collection
    {
        return $this->buildingRepository->getOccupiedUnits($buildingId);
    }

    /**
     * Get units by building
     */
    public function getUnitsByBuilding(int $buildingId): Collection
    {
        return $this->buildingRepository->getUnitsByBuilding($buildingId);
    }

    /**
     * Get unit details with contract information
     */
    public function getUnitDetails(int $unitId): Unit
    {
        $unit = $this->buildingRepository->findUnitOrFail($unitId);
        
        return $unit->load([
            'building',
            'contracts' => function ($query) {
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now())
                      ->with(['client', 'broker']);
            }
        ]);
    }
}


