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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // مثل: create, update, delete, view, login, logout
            $table->string('model_type')->nullable(); // مثل: Contract, Tenant, Building
            $table->unsignedBigInteger('model_id')->nullable(); // ID للـ model
            $table->text('description')->nullable(); // وصف تفصيلي للإجراء
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('old_values')->nullable(); // القيم القديمة قبل التعديل
            $table->json('new_values')->nullable(); // القيم الجديدة بعد التعديل
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
