<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id')->nullable()->after('id');

        $table->foreign('company_id')
              ->references('id')
              ->on('companies')
              ->onDelete('set null'); // أو onDelete('cascade') حسب ما تفضل
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['company_id']);
        $table->dropColumn('company_id');
    });
}
};
