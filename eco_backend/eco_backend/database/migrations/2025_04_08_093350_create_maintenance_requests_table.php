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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade'); // [ref: > companies.id]
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade'); // [ref: > users.id]
            $table->foreignId('category_id')->constrained('maintenance_categories')->onDelete('cascade'); // [ref: > maintenance_categories.id]
            $table->text('description');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected']);
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
