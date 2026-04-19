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
        Schema::create('visits', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->string('visitor_name'); // اسم الزائر
            $table->timestamp('visitor_access_at')->useCurrent(); // تاريخ ووقت دخول الزائر
            $table->timestamp('visitor_expires_at')->nullable();
            $table->foreignId('created_by_id')->constrained('users')->onDelete('cascade'); // [ref: > users.id]
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
