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
        Schema::table('maintenance_requests', function (Blueprint $table) {
            // Check if company_id column exists before trying to drop it
            if (Schema::hasColumn('maintenance_requests', 'company_id')) {
                // Check if foreign key constraint exists before trying to drop it
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'maintenance_requests' 
                    AND COLUMN_NAME = 'company_id' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                if (!empty($foreignKeys)) {
                    $table->dropForeign(['company_id']);
                }
                
                // Drop the company_id column
                $table->dropColumn('company_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            // Add back the company_id column
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            // Add back the foreign key constraint
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
};
