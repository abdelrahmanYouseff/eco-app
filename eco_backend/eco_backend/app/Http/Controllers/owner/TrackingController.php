<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        return view('owner.tracking.index', [
            'selectedUserId' => $request->integer('user_id') ?: null,
        ]);
    }

    public function active(Request $request): JsonResponse
    {
        $activeMinutes = 5;
        $since = now()->subMinutes($activeMinutes);

        $latestPerUser = DB::table('user_locations')
            ->selectRaw('user_id, MAX(recorded_at) AS max_recorded_at')
            ->where('recorded_at', '>=', $since)
            ->groupBy('user_id');

        $rows = DB::table('user_locations as ul')
            ->joinSub($latestPerUser, 'lu', function ($join) {
                $join->on('ul.user_id', '=', 'lu.user_id')
                    ->on('ul.recorded_at', '=', 'lu.max_recorded_at');
            })
            ->join('users as u', 'u.id', '=', 'ul.user_id')
            ->orderByDesc('ul.recorded_at')
            ->get([
                'u.id as user_id',
                'u.name as user_name',
                'ul.latitude',
                'ul.longitude',
                'ul.accuracy',
                'ul.recorded_at',
            ]);

        return response()->json([
            'success' => true,
            'meta' => [
                'active_minutes' => $activeMinutes,
            ],
            'data' => $rows->map(function ($r) {
                return [
                    'user_id' => (int) $r->user_id,
                    'user_name' => $r->user_name,
                    'latitude' => (float) $r->latitude,
                    'longitude' => (float) $r->longitude,
                    'accuracy' => $r->accuracy !== null ? (int) $r->accuracy : null,
                    'recorded_at' => Carbon::parse($r->recorded_at)->toIso8601String(),
                ];
            }),
        ]);
    }

    public function latest(Request $request, User $user): JsonResponse
    {
        $location = UserLocation::query()
            ->where('user_id', $user->id)
            ->orderByDesc('recorded_at')
            ->first();

        if (!$location) {
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'latitude' => (float) $location->latitude,
                'longitude' => (float) $location->longitude,
                'accuracy' => $location->accuracy,
                'recorded_at' => $location->recorded_at?->toIso8601String(),
            ],
        ]);
    }
}
