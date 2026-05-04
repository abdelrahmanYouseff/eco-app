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
        Schema::table('rent_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('rent_payments', 'fixed_amounts')) {
                $table->decimal('fixed_amounts', 15, 2)->default(0)->after('vat_value');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rent_payments', function (Blueprint $table) {
            if (Schema::hasColumn('rent_payments', 'fixed_amounts')) {
                $table->dropColumn('fixed_amounts');
            }
        });
    }
};
