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
                'owner_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
