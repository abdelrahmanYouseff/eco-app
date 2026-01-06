<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\PropertyManagement\Models\Unit;
use App\PropertyManagement\Services\Buildings\BuildingService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct(
        private BuildingService $buildingService
    ) {}

    public function index(Request $request)
    {
        $buildingId = $request->input('building_id');
        $unitType = $request->input('unit_type');
        $status = $request->input('status');
        $floorNumber = $request->input('floor_number');
        
        $query = Unit::with(['building', 'contracts']);
        
        // فلتر حسب المبنى
        if ($buildingId) {
            $query->where('building_id', $buildingId);
        }
        
        // فلتر حسب نوع الوحدة
        if ($unitType) {
            $query->where('unit_type', $unitType);
        }
        
        // فلتر حسب الطابق
        if ($floorNumber) {
            $query->where('floor_number', $floorNumber);
        }
        
        $units = $query->get();
        
        // فلتر حسب الحالة (متاحة/مشغولة)
        if ($status) {
            $units = $units->filter(function($unit) use ($status) {
                $hasActiveContract = $unit->contracts->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->count() > 0;
                
                if ($status === 'available') {
                    return !$hasActiveContract;
                } elseif ($status === 'occupied') {
                    return $hasActiveContract;
                }
                return true;
            });
        }
        
        $buildings = Building::all(['id', 'name']);
        $floors = Unit::distinct()->orderBy('floor_number')->pluck('floor_number');
        
        return view('property_management.units.index', compact(
            'units', 
            'buildings', 
            'buildingId', 
            'unitType', 
            'status', 
            'floorNumber',
            'floors'
        ));
    }

    public function create()
    {
        $buildings = Building::all(['id', 'name']);
        return view('property_management.units.create', compact('buildings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'unit_number' => 'required|string',
            'floor_number' => 'required|integer',
            'unit_type' => 'required|in:مكتب,شقة,محل',
            'area' => 'required|numeric|min:0',
            'direction' => 'nullable|string',
            'parking_lots' => 'integer|min:0',
            'mezzanine' => 'boolean',
            'finishing_type' => 'nullable|in:furnished,unfurnished',
            'ac_units' => 'integer|min:0',
            'current_electricity_meter' => 'nullable|string',
            'current_water_meter' => 'nullable|string',
            'current_gas_meter' => 'nullable|string',
        ]);

        try {
            $unit = $this->buildingService->createUnit($validated);
            
            // تحديث units_count في المبنى
            $building = Building::find($validated['building_id']);
            if ($building) {
                $building->increment('units_count');
            }
            
            return redirect()->route('property-management.units.index')
                ->with('success', 'Unit created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $unit = $this->buildingService->getUnitDetails($id);
        return view('property_management.units.show', compact('unit'));
    }

    public function edit($id)
    {
        $unit = Unit::with('building')->findOrFail($id);
        $buildings = Building::all(['id', 'name']);
        return view('property_management.units.edit', compact('unit', 'buildings'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'unit_number' => 'required|string',
            'floor_number' => 'required|integer',
            'unit_type' => 'required|in:مكتب,شقة,محل',
            'area' => 'required|numeric|min:0',
            'direction' => 'nullable|string',
            'parking_lots' => 'integer|min:0',
            'mezzanine' => 'boolean',
            'finishing_type' => 'nullable|in:furnished,unfurnished',
            'ac_units' => 'integer|min:0',
            'current_electricity_meter' => 'nullable|string',
            'current_water_meter' => 'nullable|string',
            'current_gas_meter' => 'nullable|string',
        ]);

        try {
            $unit = Unit::findOrFail($id);
            $unit = $this->buildingService->updateUnit($unit, $validated);
            return redirect()->route('property-management.units.index')
                ->with('success', 'Unit updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $building = $unit->building;
            
            // Check if unit has active contracts
            $activeContracts = $unit->contracts()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count();

            if ($activeContracts > 0) {
                return redirect()->route('property-management.units.index')
                    ->with('error', 'Cannot delete unit with active contracts');
            }
            
            // حذف الوحدة
            $unit->delete();
            
            // تحديث عدد الوحدات في المبنى
            if ($building) {
                $currentUnitsCount = $building->units_count ?? 0;
                if ($currentUnitsCount > 0) {
                    $building->update([
                        'units_count' => $currentUnitsCount - 1
                    ]);
                }
            }
            
            return redirect()->route('property-management.units.index')
                ->with('success', 'Unit deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('property-management.units.index')
                ->with('error', $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:units,id',
        ]);

        try {
            $unitIds = $request->input('unit_ids');
            $units = Unit::with(['building', 'contracts'])->whereIn('id', $unitIds)->get();
            
            $deletedCount = 0;
            $skippedCount = 0;
            $buildingUnitsCount = [];

            foreach ($units as $unit) {
                // Check if unit has active contracts
                $activeContracts = $unit->contracts()
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->count();

                if ($activeContracts > 0) {
                    $skippedCount++;
                    continue;
                }

                // Track building for units_count update
                $buildingId = $unit->building_id;
                if (!isset($buildingUnitsCount[$buildingId])) {
                    $buildingUnitsCount[$buildingId] = 0;
                }
                $buildingUnitsCount[$buildingId]++;

                // Delete unit
                $unit->delete();
                $deletedCount++;
            }

            // Update units_count for affected buildings
            foreach ($buildingUnitsCount as $buildingId => $count) {
                $building = Building::find($buildingId);
                if ($building) {
                    $currentUnitsCount = $building->units_count ?? 0;
                    if ($currentUnitsCount >= $count) {
                        $building->update([
                            'units_count' => $currentUnitsCount - $count
                        ]);
                    }
                }
            }

            $message = "تم حذف {$deletedCount} وحدة بنجاح";
            if ($skippedCount > 0) {
                $message .= " (تم تخطي {$skippedCount} وحدة لوجود عقود نشطة)";
            }

            return redirect()->route('property-management.units.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('property-management.units.index')
                ->with('error', 'حدث خطأ أثناء حذف الوحدات: ' . $e->getMessage());
        }
    }
}

