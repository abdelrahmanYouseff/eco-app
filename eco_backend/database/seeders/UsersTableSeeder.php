<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Owner Admin',
            'email' => 'owner@eco.com',
            'phone' => '0555555555',
            'password' => Hash::make('password123'),
            'role' => 'building_admin',
            // 'company_id' => null, // أو id حقيقي لو مطلوب
        ]);
    }
}
