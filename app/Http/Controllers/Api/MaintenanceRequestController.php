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

        // البحث عن فئة الصيانة أو إنشاؤها إذا لم تكن موجودة
        $category = MaintenanceCategory::firstOrCreate([
            'name' => $validated['service_name']
        ]);

        // إنشاء طلب الصيانة
        $maintenanceRequest = MaintenanceRequest::create([
            'company_id' => Auth::user()->company_id, // استخدام company_id للمستخدم المسجل دخوله
            'requested_by' => Auth::id(), // استخدام ID المستخدم المسجل دخوله
            'category_id' => $category->id,
            'description' => $validated['description'],
            'status' => 'pending', // تلقائيًا
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

    // دالة لجلب طلبات الصيانة للمستخدم الحالي
    public function index()
    {
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
