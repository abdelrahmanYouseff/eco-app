<?php

namespace App\PropertyManagement\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\PropertyManagement\Models\Broker;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    public function index(Request $request)
    {
        $query = Broker::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('representative_name', 'like', "%{$search}%")
                  ->orWhere('cr_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $brokers = $query->withCount('contracts')->paginate(15);
        
        return view('property_management.brokers.index', compact('brokers'));
    }

    public function create()
    {
        return view('property_management.brokers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'representative_name' => 'nullable|string|max:255',
            'cr_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        try {
            Broker::create($validated);
            return redirect()->route('property-management.brokers.index')
                ->with('success', 'تم إضافة الوسيط بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة الوسيط: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $broker = Broker::with(['contracts.client', 'contracts.unit', 'contracts.building'])
            ->findOrFail($id);
        
        return view('property_management.brokers.show', compact('broker'));
    }

    public function edit($id)
    {
        $broker = Broker::findOrFail($id);
        return view('property_management.brokers.edit', compact('broker'));
    }

    public function update(Request $request, $id)
    {
        $broker = Broker::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'representative_name' => 'nullable|string|max:255',
            'cr_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        try {
            $broker->update($validated);
            return redirect()->route('property-management.brokers.index')
                ->with('success', 'تم تحديث بيانات الوسيط بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث بيانات الوسيط: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $broker = Broker::findOrFail($id);
            
            // Check if broker has contracts
            if ($broker->contracts()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'لا يمكن حذف الوسيط لأنه مرتبط بعقود');
            }
            
            $broker->delete();
            return redirect()->route('property-management.brokers.index')
                ->with('success', 'تم حذف الوسيط بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الوسيط: ' . $e->getMessage());
        }
    }
}


