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
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->date('due_date');
            $table->date('issued_date');
            $table->decimal('total_value', 15, 2);
            $table->decimal('rent_value', 15, 2);
            $table->decimal('services_value', 15, 2)->default(0);
            $table->decimal('vat_value', 15, 2)->default(0);
            $table->enum('status', ['paid', 'unpaid', 'partially_paid'])->default('unpaid');
            $table->date('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_payments');
    }
};
