<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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

            // Trigger عملية فتح البوابة (مثلاً عبر إشارة لجهاز)
            // يمكنك دمجها مع API لجهاز الهاردوير

            return response()->json(['message' => 'Gate opened. Welcome!']);
        }

        return response()->json(['message' => 'Already inside.'], 403);
    }
}
