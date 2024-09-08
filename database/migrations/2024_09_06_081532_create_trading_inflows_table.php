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
        Schema::create('trading_inflows', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('time',length:2);
            $table->string('attendant');
            $table->string('plate_number');
            $table->string('vehicle_type');
            $table->string('name');
            $table->string('commodity');
            $table->integer('volume');
            $table->integer('barangay');
            $table->integer('municipality');
            $table->integer('province');
            $table->integer('region');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_inflows');
    }
};
