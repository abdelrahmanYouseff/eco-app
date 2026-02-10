<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to add 'editor' role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('building_admin', 'company_admin', 'employee', 'visitor', 'accountant', 'editor') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'editor' from enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('building_admin', 'company_admin', 'employee', 'visitor', 'accountant') NOT NULL");
    }
};
