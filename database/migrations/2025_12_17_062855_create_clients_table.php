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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('client_type', ['فرد', 'شركة']);
            $table->string('id_number_or_cr');
            $table->string('id_type')->nullable();
            $table->string('nationality')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile');
            $table->text('national_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
