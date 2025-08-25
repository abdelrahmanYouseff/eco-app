<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Models\Company;

class TestMaintenanceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user and company for testing
        $user = User::first();
        $company = Company::first();
        
        if (!$user || !$company) {
            $this->command->error('No users or companies found. Please run UserSeeder and CompanySeeder first.');
            return;
        }

        // Create test maintenance requests
        $testRequests = [
            [
                'title' => 'HVAC System Repair',
                'company_name' => $company->name,
                'requested_by' => $user->id,
                'description' => 'The air conditioning system in the main office is not working properly. Temperature is too high and affecting productivity.',
                'status' => 'pending',
            ],
            [
                'title' => 'Electrical Issue',
                'company_name' => $company->name,
                'requested_by' => $user->id,
                'description' => 'Power outlet in conference room is not working. Need urgent repair for upcoming meeting.',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Plumbing Problem',
                'company_name' => $company->name,
                'requested_by' => $user->id,
                'description' => 'Water leak in the bathroom. Ceiling is getting damaged and needs immediate attention.',
                'status' => 'completed',
            ],
            [
                'title' => 'Internet Connectivity',
                'company_name' => $company->name,
                'requested_by' => $user->id,
                'description' => 'WiFi signal is weak in the back office area. Need to install additional access points.',
                'status' => 'pending',
            ],
            [
                'title' => 'Cleaning Service',
                'company_name' => $company->name,
                'requested_by' => $user->id,
                'description' => 'Request for deep cleaning of the entire office space including carpets and windows.',
                'status' => 'rejected',
            ],
        ];

        foreach ($testRequests as $request) {
            MaintenanceRequest::create($request);
        }

        $this->command->info('Test maintenance requests created successfully!');
    }
}
