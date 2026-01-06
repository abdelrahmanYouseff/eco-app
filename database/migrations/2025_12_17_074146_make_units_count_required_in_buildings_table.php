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
        // Set default value for existing records
        DB::table('buildings')->whereNull('units_count')->update(['units_count' => 0]);
        
        Schema::table('buildings', function (Blueprint $table) {
            $table->integer('units_count')->nullable(false)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->integer('units_count')->nullable()->change();
        });
    }
};
