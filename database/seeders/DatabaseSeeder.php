<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(BasicDataSeeder::class);
        // $this->call(CompanyAdminUserTableSeeder::class);
        $this->call(BuildingSeeder::class);
        $this->call(EmployeeUserSeeder::class);
        $this->call(TestMaintenanceRequestSeeder::class);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
