<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrackingTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'tracking.test@example.com'],
            [
                'name' => 'Tracking Test User',
                'phone' => '0000000000',
                'password' => Hash::make('password'),
                'role' => 'building_admin',
                'company_id' => null,
            ]
        );

        UserLocation::query()->create([
            'user_id' => $user->id,
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'accuracy' => 10,
            'recorded_at' => now(),
        ]);
    }
}
