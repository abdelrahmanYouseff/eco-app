<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('contracts', 'electricity_meter_claim_sent_at')) {
                $table->timestamp('electricity_meter_claim_sent_at')->nullable()->after('broker_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'electricity_meter_claim_sent_at')) {
                $table->dropColumn('electricity_meter_claim_sent_at');
            }
        });
    }
};
