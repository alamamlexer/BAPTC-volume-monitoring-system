<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inflow;

class InflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inflow::create([
            // 'date' =>'2024-09-10',
            // 'time' =>'PM',
            // 'transaction_status' =>'trading',
            // 'attendant' =>'Lexer Alam-am',
            // 'plate_number' =>'AAA 111',
            // 'vehicle_type' =>'A1',
            // 'name' => 'Alvin',
            // 'commodity' =>'',
            // 'volume' =>,
            // 'barangay' =>,
            // 'municipality' =>,
            // 'province' =>,
            // 'region' =>,
            // 'name' => 'User',
            // 'plate_number' => '000 AAA',
            // 'password' => '123',
            // 'contact_number' => '0999999999',
            // 'type' => 0,
        ]);
    }
}
