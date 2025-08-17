<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Log;

class GatesController extends Controller
{
    public function accessLogs()
    {
        // جلب جميع المستخدمين مع معلومات الشركة
        $users = User::with('company')
                    ->whereIn('role', ['employee', 'company_admin'])
                    ->orderBy('is_inside', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->get();

        return view('owner.gates.access_logs', compact('users'));
    }

    public function getAccessLogs()
    {
        // API endpoint لجلب سجلات الدخول والخروج
        $logs = Log::with('user')
                  ->orderBy('created_at', 'desc')
                  ->limit(50)
                  ->get();

        return response()->json([
            'status' => true,
            'data' => $logs
        ]);
    }

    public function getUserStatus($userId)
    {
        $user = User::findOrFail($userId);

        return response()->json([
            'status' => true,
            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'is_inside' => $user->is_inside,
                'last_activity' => $user->updated_at,
                'badge_id' => $user->badge_id
            ]
        ]);
    }

    public function generateQRCode($badgeId)
    {
        // يمكن إضافة منطق إنشاء QR code هنا إذا لزم الأمر
        return response()->json([
            'status' => true,
            'badge_id' => $badgeId,
            'qr_code_data' => $badgeId
        ]);
    }
}
