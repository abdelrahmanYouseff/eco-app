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
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->string('name');
            $table->integer('floor_number');
            $table->string('office_number', 20);
            $table->foreignId('admin_user_id')->constrained('users')->onDelete('cascade'); // [ref: > users.id]
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade'); // [ref: > buildings.id]
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
