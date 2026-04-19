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
        Schema::table('buildings', function (Blueprint $table) {
            $table->integer('floors_count')->default(1)->after('units_count');
        });
        
        // Set default value for existing records
        DB::table('buildings')->whereNull('floors_count')->orWhere('floors_count', 0)->update(['floors_count' => 1]);
        
        // Make it required after setting defaults
        Schema::table('buildings', function (Blueprint $table) {
            $table->integer('floors_count')->nullable(false)->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('floors_count');
        });
    }
};
