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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'badge_id')) {
                $table->string('badge_id')->unique()->after('role'); // كود خاص لكل موظف
            }
            if (!Schema::hasColumn('users', 'is_inside')) {
                $table->boolean('is_inside')->default(false); // بيدل إذا كان الموظف داخل المبنى أو لأ
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'badge_id')) {
                $table->dropColumn('badge_id');
            }
            if (Schema::hasColumn('users', 'is_inside')) {
                $table->dropColumn('is_inside');
            }
        });
    }
};
