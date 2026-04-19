<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('companies')->insert([
            [
                'id' => 1,
                'name' => 'Advanced Line Technology',
                'floor_number' => 2,
                'office_number' => '001',
                'admin_user_id' => 1,
                'building_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
            // أضف بيانات أخرى لو تحب
        ]);
    }
}
