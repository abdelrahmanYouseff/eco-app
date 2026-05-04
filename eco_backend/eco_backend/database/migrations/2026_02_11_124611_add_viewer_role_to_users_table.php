<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add 'viewer' role to the users table enum.
     */
    public function up(): void
    {
        // Modify the enum to add 'viewer' role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('building_admin', 'company_admin', 'employee', 'visitor', 'accountant', 'editor', 'viewer') NOT NULL");
    }

    /**
     * Reverse the migrations.
     * Remove 'viewer' from enum.
     */
    public function down(): void
    {
        // Remove 'viewer' from enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('building_admin', 'company_admin', 'employee', 'visitor', 'accountant', 'editor') NOT NULL");
    }
};
