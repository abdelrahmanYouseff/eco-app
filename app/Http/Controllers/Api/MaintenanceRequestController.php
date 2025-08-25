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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'nullable|string|max:255',
        ]);

        // التحقق من أن المستخدم مسجل دخوله
        if (!Auth::user()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to submit maintenance requests',
                'error' => 'User must be logged in'
            ], 401);
        }

        // إنشاء طلب الصيانة
        $maintenanceRequest = MaintenanceRequest::create([
            'title' => $validated['title'],
            'company_name' => $validated['company_name'] ?? (Auth::user()->company ? Auth::user()->company->name : 'Not specified'),
            'requested_by' => Auth::id(),
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Maintenance request submitted successfully',
            'data' => [
                'id' => $maintenanceRequest->id,
                'title' => $maintenanceRequest->title,
                'company_name' => $maintenanceRequest->company_name,
                'requested_by' => Auth::user()->name,
                'description' => $maintenanceRequest->description,
                'status' => $maintenanceRequest->status,
                'created_at' => $maintenanceRequest->created_at->format('Y-m-d H:i:s')
            ]
        ], 201);
    }

    // Get all maintenance requests for the current user
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::user()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to view maintenance requests',
                'error' => 'User must be logged in'
            ], 401);
        }

        $requests = MaintenanceRequest::where('requested_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Maintenance requests retrieved successfully',
            'data' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'title' => $request->title,
                    'company_name' => $request->company_name,
                    'requested_by' => $request->requestedBy ? $request->requestedBy->name : 'Unknown',
                    'description' => $request->description,
                    'status' => $request->status,
                    'created_at' => $request->created_at->format('Y-m-d H:i:s')
                ];
            }),
            'total' => $requests->count()
        ]);
    }

    // Get all maintenance requests (for admins)
    public function getAllRequests()
    {
        // Check if user is authenticated and is admin
        if (!Auth::user()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required',
                'error' => 'User must be logged in'
            ], 401);
        }

        // Check if user is admin (building_admin or company_admin)
        if (!in_array(Auth::user()->role, ['building_admin', 'company_admin'])) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied',
                'error' => 'Only admins can view all requests'
            ], 403);
        }

        $requests = MaintenanceRequest::with('requestedBy')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'All maintenance requests retrieved successfully',
            'data' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'title' => $request->title,
                    'company_name' => $request->company_name,
                    'requested_by' => $request->requestedBy ? $request->requestedBy->name : 'Unknown',
                    'description' => $request->description,
                    'status' => $request->status,
                    'created_at' => $request->created_at->format('Y-m-d H:i:s')
                ];
            }),
            'total' => $requests->count(),
            'statistics' => [
                'pending' => $requests->where('status', 'pending')->count(),
                'in_progress' => $requests->where('status', 'in_progress')->count(),
                'completed' => $requests->where('status', 'completed')->count(),
                'rejected' => $requests->where('status', 'rejected')->count()
            ]
        ]);
    }

    // Update request status
    public function updateStatus(Request $request, $id)
    {
        // Check if user is authenticated and is admin
        if (!Auth::user()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required',
                'error' => 'User must be logged in'
            ], 401);
        }

        if (!in_array(Auth::user()->role, ['building_admin', 'company_admin'])) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied',
                'error' => 'Only admins can update request status'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,rejected'
        ]);

        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        $maintenanceRequest->update(['status' => $validated['status']]);

        return response()->json([
            'status' => true,
            'message' => 'Request status updated successfully',
            'data' => [
                'id' => $maintenanceRequest->id,
                'title' => $maintenanceRequest->title,
                'status' => $maintenanceRequest->status,
                'updated_at' => $maintenanceRequest->updated_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    // Test method without authentication (for testing purposes)
    public function testStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'nullable|string|max:255',
        ]);

        // Create maintenance request with a default user (for testing)
        $maintenanceRequest = MaintenanceRequest::create([
            'title' => $validated['title'],
            'company_name' => $validated['company_name'] ?? 'Test Company',
            'requested_by' => 1, // Default user ID
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Test maintenance request created successfully',
            'data' => [
                'id' => $maintenanceRequest->id,
                'title' => $maintenanceRequest->title,
                'company_name' => $maintenanceRequest->company_name,
                'requested_by' => 'Test User',
                'description' => $maintenanceRequest->description,
                'status' => $maintenanceRequest->status,
                'created_at' => $maintenanceRequest->created_at->format('Y-m-d H:i:s')
            ]
        ], 201);
    }
}
