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
            $table->date('date');
            $table->string('time');
            $table->string('transaction_type'); 
            $table->string('transaction_status'); 
            $table->foreignId('staff_id')->constrained('staff','staff_id');  
            $table->foreignId('commodity_id')->constrained('commodities','commodity_id'); 
            $table->decimal('volume', 10, 2);
            $table->string('plate_number')->nullable();
            $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types','vehicle_type_id'); 
            $table->string('name')->nullable();
            $table->foreignId('facilitator_id')->nullable()->constrained('facilitators','facilitator_id'); 
            $table->string('barangay'); 
            $table->string('municipality'); 
            $table->string('province'); 
            $table->string('region'); 
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
