<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class CompanyAdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Company Admin',
            'email' => 'admin@eco.com',
            'phone' => '0535815072',
            'password' => Hash::make('password123'),
            'role' => 'company_admin',
            // 'company_id' => null, // أو id حقيقي لو مطلوب
        ]);
    }
}
