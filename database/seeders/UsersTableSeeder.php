<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Admin',
                'phone' => '0555555555',
                'password' => Hash::make('password123'),
                'role' => 'building_admin',
                'badge_id' => 'ADMIN001',
                // 'company_id' => null, // أو id حقيقي لو مطلوب
            ]
        );

        User::updateOrCreate(
            ['email' => 'account@eco.com'],
            [
                'name' => 'Accountant',
                'phone' => '0500000000',
                'password' => Hash::make('password123'),
                'role' => 'accountant',
                'badge_id' => 'ACC001',
            ]
        );
    }
}
