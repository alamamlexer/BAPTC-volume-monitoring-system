<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleType;
class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleType::create([
            'vehicle_type_id' => '1',
            'vehicle_type' => 'Single Tire',
        ]);
        VehicleType::create([
            'vehicle_type_id' => '2',
            'vehicle_type' => 'Double Tire',
        ]);
        VehicleType::create([
            'vehicle_type_id' => '3',
            'vehicle_type' => 'Forward',
        ]);
        VehicleType::create([
            'vehicle_type_id' => '4',
            'vehicle_type' => '10 Wheeler',
        ]);
    }
}
