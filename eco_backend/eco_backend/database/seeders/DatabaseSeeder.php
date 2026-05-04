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
<<<<<<< HEAD:eco_backend/eco_backend/database/seeders/DatabaseSeeder.php

=======
        $this->call(UsersTableSeeder::class);
>>>>>>> 214f60c165b7db18fb2afc2c6aa07b4401e9122d:database/seeders/DatabaseSeeder.php
        // $this->call(CompanyAdminUserTableSeeder::class);
        $this->call(EmployeeUserSeeder::class);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
