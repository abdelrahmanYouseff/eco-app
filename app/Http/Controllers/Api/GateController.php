<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Log;
use Illuminate\Support\Facades\Log as LogFacade;

class GateController extends Controller
{
    public function open(Request $request)
    {
        $user = User::where('badge_id', $request->input('badge_id'))->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // شرط: لو مش جوا المبنى حالياً
        if (!$user->is_inside) {
            $user->update(['is_inside' => true]);

            // إنشاء سجل دخول
            Log::create([
                'user_id' => $user->id,
                'type' => 'check_in',
                'scanned_by' => 'self',
                'location' => 'Main Gate',
                'qr_code_snapshot' => $request->input('qr_code_value')
            ]);

            // Trigger عملية فتح البوابة (مثلاً عبر إشارة لجهاز)
            // يمكنك دمجها مع API لجهاز الهاردوير

            return response()->json([
                'message' => 'Gate opened. Welcome!',
                'user' => [
                    'name' => $user->name,
                    'company' => $user->company ? $user->company->name : 'N/A',
                    'badge_id' => $user->badge_id
                ]
            ]);
        } else {
            // إذا كان داخل المبنى، سجل خروج
            $user->update(['is_inside' => false]);

            // إنشاء سجل خروج
            Log::create([
                'user_id' => $user->id,
                'type' => 'check_out',
                'scanned_by' => 'self',
                'location' => 'Main Gate',
                'qr_code_snapshot' => $request->input('qr_code_value')
            ]);

            return response()->json([
                'message' => 'Gate opened. Goodbye!',
                'user' => [
                    'name' => $user->name,
                    'company' => $user->company ? $user->company->name : 'N/A',
                    'badge_id' => $user->badge_id
                ]
            ]);
        }
    }

    public function gate(Request $request)
    {
        // استقبال البيانات
        $secret = $request->input('secret');
        $qrCodeValue = $request->input('qr_code_value');

        // التحقق من المفتاح السري
        if ($secret !== 'xkjalskdjalsd') {
            return response()->json([
                'allow' => false,
                'message' => 'Invalid secret key',
                'error' => 'SECRET_INVALID'
            ], 401);
        }

        // التحقق من وجود QR code value
        if (empty($qrCodeValue)) {
            return response()->json([
                'allow' => false,
                'message' => 'QR code value is required',
                'error' => 'QR_CODE_MISSING'
            ], 400);
        }

        // البحث عن المستخدم بالـ badge_id
        $user = User::where('badge_id', $qrCodeValue)->first();

        if (!$user) {
            return response()->json([
                'allow' => false,
                'message' => 'Invalid QR code - User not found',
                'error' => 'USER_NOT_FOUND'
            ], 404);
        }

        // طباعة البيانات المستلمة للـ logs
        LogFacade::info('Gate API accessed', [
            'secret' => $secret,
            'qr_code_value' => $qrCodeValue,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'timestamp' => now()
        ]);

        // إرجاع الرد الناجح
        return response()->json([
            'allow' => true,
            'message' => 'Access granted',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'company' => $user->company ? $user->company->name : 'N/A',
                'badge_id' => $user->badge_id,
                'is_inside' => $user->is_inside
            ],
            'timestamp' => now()->toISOString()
        ]);
    }
}
