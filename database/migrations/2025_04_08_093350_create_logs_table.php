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
        Schema::create('logs', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // [ref: > users.id]
            $table->enum('type', ['check_in', 'check_out']);
            $table->enum('scanned_by', ['self', 'security']);
            $table->string('location', 255);
            $table->text('qr_code_snapshot')->nullable();
            $table->timestamps(); // timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
