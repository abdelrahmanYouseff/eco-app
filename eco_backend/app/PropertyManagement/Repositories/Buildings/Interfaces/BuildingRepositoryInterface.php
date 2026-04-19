<?php

namespace App\PropertyManagement\Repositories\Buildings\Interfaces;

use App\PropertyManagement\Models\Unit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BuildingRepositoryInterface
{
    public function findUnit(int $id): ?Unit;
    public function findUnitOrFail(int $id): Unit;
    public function getAllUnits(): Collection;
    public function getUnitsByBuilding(int $buildingId): Collection;
    public function paginateUnits(int $perPage = 15): LengthAwarePaginator;
    public function createUnit(array $data): Unit;
    public function updateUnit(Unit $unit, array $data): Unit;
    public function deleteUnit(Unit $unit): bool;
    public function getAvailableUnits(int $buildingId = null): Collection;
    public function getOccupiedUnits(int $buildingId = null): Collection;
}


