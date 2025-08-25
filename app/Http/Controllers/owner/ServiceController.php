<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceCategory;
use App\Models\MaintenanceRequest;


class ServiceController extends Controller
{
    public function addNewServiceView(){
        $services = MaintenanceCategory::all();

        return view('owner.services.add_new_service', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MaintenanceCategory::create([
            'name' => $validated['name'],
        ]);

        return redirect()->back()->with('success', 'Service created successfully.');
    }

    public function destroy($id)
    {
        MaintenanceCategory::destroy($id);
        return redirect()->back()->with('deleted', 'Service deleted successfully.');
    }


    public function requestView(){
        $serviceRequest = MaintenanceRequest::with('requestedBy')->orderBy('created_at', 'desc')->get();

        return view('owner.services.request', compact('serviceRequest'));
    }

    //   public function storeRequest(Request $request)
    // {
    //     $validated = $request->validate([
    //         'company_name' => 'required|string|max:255',
    //         'requested_by' => 'required|integer',
    //         'category' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //     ]);

    //     MaintenanceRequest::create($validated);

    //     return redirect()->back()->with('success', 'Service Request Added Successfully');
    // }


}
