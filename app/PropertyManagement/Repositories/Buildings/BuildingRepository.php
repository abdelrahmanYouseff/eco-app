<?php

namespace App\PropertyManagement\Repositories\Buildings;

use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Models\Unit;
use App\PropertyManagement\Repositories\Buildings\Interfaces\BuildingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BuildingRepository implements BuildingRepositoryInterface
{
    public function findUnit(int $id): ?Unit
    {
        return Unit::with('building')->find($id);
    }

    public function findUnitOrFail(int $id): Unit
    {
        return Unit::with('building')->findOrFail($id);
    }

    public function getAllUnits(): Collection
    {
        return Unit::with(['building', 'contracts'])->get();
    }

    public function getUnitsByBuilding(int $buildingId): Collection
    {
        return Unit::where('building_id', $buildingId)
            ->with('contracts')
            ->get();
    }

    public function paginateUnits(int $perPage = 15): LengthAwarePaginator
    {
        return Unit::with(['building', 'contracts'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function createUnit(array $data): Unit
    {
        return DB::transaction(function () use ($data) {
            return Unit::create($data);
        });
    }

    public function updateUnit(Unit $unit, array $data): Unit
    {
        return DB::transaction(function () use ($unit, $data) {
            $unit->update($data);
            return $unit->fresh();
        });
    }

    public function deleteUnit(Unit $unit): bool
    {
        return DB::transaction(function () use ($unit) {
            return $unit->delete();
        });
    }

    public function getAvailableUnits(int $buildingId = null): Collection
    {
        $today = now()->toDateString();
        
        $query = Unit::whereDoesntHave('contracts', function ($q) use ($today) {
            $q->where('start_date', '<=', $today)
              ->where('end_date', '>=', $today);
        });

        if ($buildingId) {
            $query->where('building_id', $buildingId);
        }

        return $query->with('building')->get();
    }

    public function getOccupiedUnits(int $buildingId = null): Collection
    {
        $today = now()->toDateString();
        
        $query = Unit::whereHas('contracts', function ($q) use ($today) {
            $q->where('start_date', '<=', $today)
              ->where('end_date', '>=', $today);
        });

        if ($buildingId) {
            $query->where('building_id', $buildingId);
        }

        return $query->with(['building', 'contracts'])->get();
    }
}


