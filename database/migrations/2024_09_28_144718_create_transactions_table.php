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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('transaction_status')->nullable();
            $table->foreignId('staff_id')->constrained('staff','staff_id')->nullable();
            $table->foreignId('commodity_id')->constrained('commodities','commodity_id')->nullable();
            $table->decimal('volume', 15, 2)->nullable();
            $table->string('plate_number')->nullable();
            $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types','vehicle_type_id')->nullable();
            $table->string('name')->nullable();
            $table->foreignId('facilitator_id')->nullable()->constrained('facilitators','facilitator_id')->nullable();
            $table->string('barangay')->nullable(); 
            $table->string('municipality')->nullable(); 
            $table->string('province')->nullable(); 
            $table->string('region')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
