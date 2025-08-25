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

        // التحقق من أن المستخدم مسجل دخوله
        if (!Auth::user()) {
            return response()->json([
                'status' => false,
                'message' => 'يجب تسجيل الدخول لإرسال طلب صيانة',
                'error' => 'Authentication required to submit maintenance requests'
            ], 401);
        }

        // التحقق من أن المستخدم مرتبط بشركة
        if (!Auth::user()->company_id) {
            return response()->json([
                'status' => false,
                'message' => 'المستخدم يجب أن يكون مرتبط بشركة لإرسال طلب صيانة',
                'error' => 'User must be associated with a company to submit maintenance requests'
            ], 400);
        }

        // إنشاء طلب الصيانة
        $maintenanceRequest = MaintenanceRequest::create([
            'title' => $validated['service_name'], // استخدام service_name كعنوان
            'company_name' => Auth::user()->company ? Auth::user()->company->name : 'غير محدد',
            'requested_by' => Auth::id(),
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال طلب الصيانة بنجاح',
            'data' => [
                'id' => $maintenanceRequest->id,
                'service_name' => $maintenanceRequest->title,
                'description' => $maintenanceRequest->description,
                'status' => $maintenanceRequest->status,
                'created_at' => $maintenanceRequest->created_at,
                'requested_by' => Auth::user()->name,
                'company_name' => $maintenanceRequest->company_name
            ]
        ], 201);
    }

    // دالة لجلب طلبات الصيانة للمستخدم الحالي
    public function index()
    {
        // التحقق من أن المستخدم مسجل دخوله
        if (!Auth::user()) {
            return response()->json([
                'status' => false,
                'message' => 'يجب تسجيل الدخول لعرض طلبات الصيانة',
                'error' => 'Authentication required to view maintenance requests'
            ], 401);
        }

        $requests = MaintenanceRequest::where('requested_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'service_name' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status,
                    'created_at' => $request->created_at,
                    'company_name' => $request->company_name
                ];
            })
        ]);
    }
}
