<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('buildings')->insert([
            [
                'id' => 1,
                'name' => 'ECO',
                'address' => 'Main Street, Downtown',
                'phone' => '+1234567890',
                'description' => 'Main ECO building',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'ECO Properties',
                'address' => 'Business District, City Center',
                'phone' => '+1234567891',
                'description' => 'ECO Properties building for real estate services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
