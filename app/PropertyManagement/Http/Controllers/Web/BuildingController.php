<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\User;
use App\PropertyManagement\Models\Unit;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    use LogsActivity;
    public function index()
    {
        $buildings = Building::with('owner')->paginate(15);
        return view('property_management.buildings.index', compact('buildings'));
    }

    public function create()
    {
        $owners = User::where('role', 'building_admin')->get(['id', 'name']);
        return view('property_management.buildings.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
            'floors_count' => 'required|integer|min:1',
            'address' => 'nullable|string',
        ]);

        // Set default units_count to 0
        $validated['units_count'] = 0;

        try {
            $building = Building::create($validated);

            // Log activity
            $this->logActivity(
                'create',
                $building,
                "تم إنشاء مبنى جديد: {$building->name}"
            );

            return redirect()->route('property-management.buildings.index')
                ->with('success', 'تم إنشاء المبنى بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $building = Building::with(['owner', 'units.contracts' => function($query) {
            $query->where('start_date', '<=', now())
                  ->where('end_date', '>=', now())
                  ->orderBy('created_at', 'desc');
        }, 'units.contracts.client', 'companies'])->findOrFail($id);

        $totalUnits = $building->units_count ?? 0;
        $actualUnits = $building->units->count();
        $availableUnits = $building->units->filter(function($unit) {
            return $unit->contracts->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count() == 0;
        })->count();
        $occupiedUnits = $actualUnits - $availableUnits;

        return view('property_management.buildings.show', compact('building', 'totalUnits', 'availableUnits', 'occupiedUnits'));
    }

    public function edit($id)
    {
        $building = Building::findOrFail($id);
        $owners = User::where('role', 'building_admin')->get(['id', 'name']);
        return view('property_management.buildings.edit', compact('building', 'owners'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
            'units_count' => 'required|integer|min:0',
            'floors_count' => 'required|integer|min:1',
            'address' => 'nullable|string',
        ]);

        try {
            $building = Building::findOrFail($id);
            $oldValues = $building->toArray();
            $oldUnitsCount = $building->units_count;
            $building->update($validated);

            // Log activity
            $this->logActivity(
                'update',
                $building,
                "تم تحديث المبنى: {$building->name}",
                $oldValues,
                $building->fresh()->toArray()
            );

            // إنشاء وحدات جديدة إذا زاد العدد
            if ($validated['units_count'] > $oldUnitsCount) {
                $unitsToCreate = $validated['units_count'] - $oldUnitsCount;
                $this->createUnitsForBuilding($building, $unitsToCreate, $oldUnitsCount);
            }

            return redirect()->route('property-management.buildings.index')
                ->with('success', 'تم تحديث المبنى بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $building = Building::findOrFail($id);

            // Check if building has units
            if ($building->units->count() > 0) {
                return redirect()->route('property-management.buildings.index')
                    ->with('error', 'لا يمكن حذف المبنى لأنه يحتوي على وحدات');
            }

            // Log activity before deletion
            $this->logActivity(
                'delete',
                $building,
                "تم حذف المبنى: {$building->name}"
            );

            $building->delete();
            return redirect()->route('property-management.buildings.index')
                ->with('success', 'تم حذف المبنى بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('property-management.buildings.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * إنشاء وحدات تلقائياً للمبنى
     */
    private function createUnitsForBuilding(Building $building, int $count, int $startFrom = 0)
    {
        $existingUnits = Unit::where('building_id', $building->id)->count();
        $unitsToCreate = $count - $existingUnits;

        if ($unitsToCreate <= 0) {
            return;
        }

        for ($i = 1; $i <= $unitsToCreate; $i++) {
            $unitNumber = $startFrom + $existingUnits + $i;

            Unit::create([
                'building_id' => $building->id,
                'unit_number' => (string)$unitNumber,
                'floor_number' => 1, // افتراضي
                'unit_type' => 'شقة', // افتراضي
                'area' => 0,
                'parking_lots' => 0,
                'mezzanine' => false,
                'ac_units' => 0,
            ]);
        }
    }
}

