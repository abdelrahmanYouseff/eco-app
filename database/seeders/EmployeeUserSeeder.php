<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


class EmployeeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'company_id' => 1,
                'name' => 'Abdelrahman Yousef',
                'email' => 'abdelrahman@eco.com',
                'phone' => '0535815072',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'badge_id' => '123456', // أو أي قيمة مناسبة
            ],
        ]);
    }
}
