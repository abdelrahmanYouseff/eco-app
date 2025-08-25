<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
                // Drop foreign key constraint first if it exists
                try {
                    $table->dropForeign(['company_id']);
                } catch (Exception $e) {
                    // Foreign key might not exist, continue
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
