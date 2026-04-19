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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');
            $table->string('unit_number');
            $table->integer('floor_number');
            $table->enum('unit_type', ['مكتب', 'شقة', 'محل']);
            $table->decimal('area', 10, 2);
            $table->string('direction')->nullable();
            $table->integer('parking_lots')->default(0);
            $table->boolean('mezzanine')->default(false);
            $table->enum('finishing_type', ['furnished', 'unfurnished'])->nullable();
            $table->integer('ac_units')->default(0);
            $table->string('current_electricity_meter')->nullable();
            $table->string('current_water_meter')->nullable();
            $table->string('current_gas_meter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
