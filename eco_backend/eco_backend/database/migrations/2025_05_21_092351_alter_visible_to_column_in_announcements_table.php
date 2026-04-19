<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->text('visible_to')->change(); // ✅ تحويله إلى نص طويل
        });
    }

    public function down()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('visible_to')->change(); // revert لو احتجت
        });
    }
};
