<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;

class MaintenanceRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'requested_by' => 'required|exists:users,id',
            'description' => 'required|string',
        ]);

        $request = MaintenanceRequest::create([
            'company_id' => $validated['company_id'],
            'requested_by' => $validated['requested_by'],
            'description' => $validated['description'],
            'status' => 'pending', // تلقائيًا
        ]);

        return response()->json([
            'message' => 'Maintenance request submitted successfully',
            'data' => $request
        ], 201);
    }
}
