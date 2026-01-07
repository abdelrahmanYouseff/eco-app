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
        if (Schema::hasTable('contracts')) {
            Schema::dropIfExists('contracts');
        }
        
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->enum('contract_type', ['جديد', 'مجدد']);
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_conditional')->default(false);
            $table->decimal('total_rent', 15, 2);
            $table->decimal('annual_rent', 15, 2);
            $table->decimal('deposit_amount', 15, 2)->default(0);
            $table->decimal('first_payment_amount', 15, 2)->default(0);
            $table->integer('rent_cycle'); // in months
            $table->decimal('vat_amount', 15, 2)->default(0);
            $table->decimal('general_services_amount', 15, 2)->default(0);
            $table->string('insurance_policy_number')->nullable();
            $table->unsignedBigInteger('broker_id')->nullable();
            $table->timestamps();
        });
        
        // Add foreign key constraint only if brokers table exists
        if (Schema::hasTable('brokers')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->foreign('broker_id')->references('id')->on('brokers')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
