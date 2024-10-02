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
        Schema::create('outflows', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('time',length:2);
            $table->string('transaction_type'); // outflow or inflow
            $table->string('transaction_status'); // pending or complete
            $table->string('attendant');
            $table->string('plate_number');
            $table->string('vehicle_type');
            $table->string('name');
            $table->string('commodity');
            $table->integer('volume');
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
        Schema::dropIfExists('outflows');
    }
};
