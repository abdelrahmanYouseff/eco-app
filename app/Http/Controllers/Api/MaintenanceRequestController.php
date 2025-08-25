<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceCategory;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Check if user is authenticated
        if (!Auth::user()) {
            return response()->json([
                'status' => false,
                'message' => 'يجب تسجيل الدخول لإرسال طلب صيانة',
                'error' => 'Authentication required to submit maintenance requests'
            ], 401);
        }

        // Check if user has a company_id
        if (!Auth::user()->company_id) {
            return response()->json([
                'status' => false,
                'message' => 'المستخدم يجب أن يكون مرتبط بشركة لإرسال طلب صيانة',
                'error' => 'User must be associated with a company to submit maintenance requests'
            ], 400);
        }

        // Search for maintenance category or create it if it doesn't exist
        $category = MaintenanceCategory::firstOrCreate([
            'name' => $validated['service_name']
        ]);

        // Create maintenance request
        $maintenanceRequest = MaintenanceRequest::create([
            'title' => $validated['service_name'], // Use service_name as title
            'company_id' => Auth::user()->company_id,
            'requested_by' => Auth::id(),
            'category_id' => $category->id,
            'description' => $validated['description'],
            'status' => 'pending',
            'priority' => 'medium', // Set default priority
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال طلب الصيانة بنجاح',
            'data' => [
                'id' => $maintenanceRequest->id,
                'service_name' => $category->name,
                'description' => $maintenanceRequest->description,
                'status' => $maintenanceRequest->status,
                'created_at' => $maintenanceRequest->created_at,
                'requested_by' => Auth::user()->name,
                'company_name' => Auth::user()->company->name ?? 'غير محدد'
            ]
        ], 201);
    }

    // Function to get maintenance requests for current user
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::user()) {
            return response()->json([
                'status' => false,
                'message' => 'يجب تسجيل الدخول لعرض طلبات الصيانة',
                'error' => 'Authentication required to view maintenance requests'
            ], 401);
        }

        $requests = MaintenanceRequest::where('requested_by', Auth::id())
            ->with(['category', 'company'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'service_name' => $request->category->name,
                    'description' => $request->description,
                    'status' => $request->status,
                    'created_at' => $request->created_at,
                    'company_name' => $request->company->name ?? 'غير محدد'
                ];
            })
        ]);
    }
}
