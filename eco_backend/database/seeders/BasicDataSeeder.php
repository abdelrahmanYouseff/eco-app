<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\MaintenanceCategory;

class BasicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الشركات الأساسية
        $companies = [
            ['id' => 1, 'name' => 'Test Company 1'],
            ['id' => 2, 'name' => 'Test Company 2'],
            ['id' => 3, 'name' => 'New Test Company'],
            ['id' => 4, 'name' => 'Test Company 3'],
            ['id' => 5, 'name' => 'Advanced Line Technology'],
        ];

        foreach ($companies as $companyData) {
            Company::updateOrCreate(
                ['id' => $companyData['id']],
                ['name' => $companyData['name']]
            );
        }

        // إنشاء المستخدمين الأساسيين
        $users = [
            [
                'id' => 2,
                'name' => 'System Admin',
                'email' => 'admin@eco.com',
                'password' => bcrypt('password'),
                'role' => 'system_admin',
                'company_id' => 1,
                'badge_id' => 'admin-badge-001'
            ],
            [
                'id' => 5,
                'name' => 'abdelrahman',
                'email' => 'abdelrahmanyouseff@gmail.com',
                'password' => bcrypt('123456'),
                'role' => 'company_admin',
                'company_id' => 5,
                'badge_id' => '3fcd4b88-425a-4e2b-9b8d-4b2b6ac9987b'
            ],
            [
                'id' => 8,
                'name' => 'Abdelrahman Yousef',
                'email' => 'abdelrahman@eco.com',
                'password' => bcrypt('password'),
                'role' => 'company_admin',
                'company_id' => 1,
                'badge_id' => 'user-badge-001'
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['id' => $userData['id']],
                $userData
            );
        }

        // إنشاء فئات الصيانة الأساسية
        $categories = [
            'صيانة الكهرباء',
            'صيانة التكييف',
            'صيانة السباكة',
            'صيانة المصاعد',
            'صيانة الأجهزة الإلكترونية',
            'صيانة الإنشاءات',
        ];

        foreach ($categories as $categoryName) {
            MaintenanceCategory::firstOrCreate(['name' => $categoryName]);
        }

        $this->command->info('Basic data seeded successfully!');
    }
}
