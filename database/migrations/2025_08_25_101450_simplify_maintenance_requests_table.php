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
            // إضافة عمود company_name
            if (!Schema::hasColumn('maintenance_requests', 'company_name')) {
                $table->string('company_name')->after('id');
            }
        });
        
        // حذف foreign keys باستخدام raw SQL
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'maintenance_requests' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
            AND COLUMN_NAME IN ('company_id', 'category_id')
        ");
        
        foreach ($foreignKeys as $fk) {
            try {
                DB::statement("ALTER TABLE maintenance_requests DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            } catch (\Exception $e) {
                // Foreign key might not exist, continue
            }
        }
        
        // حذف الأعمدة
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('maintenance_requests', 'company_id')) {
                $columnsToDrop[] = 'company_id';
            }
            if (Schema::hasColumn('maintenance_requests', 'category_id')) {
                $columnsToDrop[] = 'category_id';
            }
            if (Schema::hasColumn('maintenance_requests', 'priority')) {
                $columnsToDrop[] = 'priority';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            // إعادة الأعمدة المحذوفة
            $table->unsignedBigInteger('company_id')->after('id');
            $table->unsignedBigInteger('category_id')->after('company_id');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('status');
            
            // إزالة عمود company_name
            $table->dropColumn('company_name');
            
            // إعادة العلاقات
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }
};
