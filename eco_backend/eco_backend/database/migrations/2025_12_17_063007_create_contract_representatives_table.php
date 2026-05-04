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
        Schema::create('contract_representatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->enum('role', ['lessor', 'lessee']);
            $table->string('name');
            $table->string('id_type')->nullable();
            $table->string('id_number');
            $table->string('nationality')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->text('national_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_representatives');
    }
};
