<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        $location = UserLocation::create([
            'user_id' => $request->user()->id,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'accuracy' => isset($data['accuracy']) ? (int) round($data['accuracy']) : null,
            'recorded_at' => $data['recorded_at'] ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $location->id,
                'recorded_at' => $location->recorded_at?->toIso8601String(),
            ],
        ], 201);
    }
}
