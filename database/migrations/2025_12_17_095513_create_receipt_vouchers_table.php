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
        Schema::create('receipt_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('set null');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('rent_payment_id')->nullable()->constrained('rent_payments')->onDelete('set null');
            $table->date('receipt_date');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'other'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_vouchers');
    }
};
