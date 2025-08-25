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
            // إضافة عمود company_name
            $table->string('company_name')->after('id');
            
            // إزالة الأعمدة غير المطلوبة
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'category_id', 'priority']);
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
