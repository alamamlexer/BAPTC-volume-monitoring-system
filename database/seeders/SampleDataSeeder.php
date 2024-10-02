<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use App\Models\VehicleType;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Commodity;
use App\Models\Location;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        //Staff
        Staff::create([
            'staff_id' => '1',
            'staff_name' => 'Lexer',
            'email' => 'lexer@gmail.com',
            'contact_number' => '0999999998',
        ]);
        Staff::create([
            'staff_id' => '2',
            'staff_name' => 'Kimjo',
            'email' => 'kimjo@gmail.com',
            'contact_number' => '0999999992',
        ]);
        Staff::create([
            'staff_id' => '3',
            'staff_name' => 'trixan',
            'email' => 'trixan@gmail.com',
            'contact_number' => '0999999991',
        ]); 
            
        //Vehicle Type
        VehicleType::create([
            'vehicle_type_id' => '1',
            'vehicle_type_name' => 'Single Tire',
        ]);
        VehicleType::create([
            'vehicle_type_id' => '2',
            'vehicle_type_name' => 'Double Tire',
        ]);
        VehicleType::create([
            'vehicle_type_id' => '3',
            'vehicle_type_name' => 'Forward',
        ]);
        VehicleType::create([
            'vehicle_type_id' => '4',
            'vehicle_type_name' => '10 Wheeler',
        ]);
        
        //Vehicle
        Vehicle::create([
            'vehicle_id' => '1',
            'plate_number' => 'AAA 111',
            'vehicle_name' => 'Farmer 1',
            'vehicle_type_id' => '1',
        ]);
        Vehicle::create([
            'vehicle_id' => '2',
            'plate_number' => 'AAA 112',
            'vehicle_name' => 'Farmer 2',
            'vehicle_type_id' => '2',
        ]);
        Vehicle::create([
            'vehicle_id' => '3',
            'plate_number' => 'AAA 113',
            'vehicle_name' => 'Farmer 3',
            'vehicle_type_id' => '3',
        ]);
        Vehicle::create([
            'vehicle_id' => '4',
            'plate_number' => 'AAA 114',
            'vehicle_name' => 'Farmer 4',
            'vehicle_type_id' => '1',
        ]);
        
        //Location
        Location::create([
            'location_id' => '1',
            'barangay' => 'Ampusongan',
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        Location::create([
            'location_id' => '2',
            'barangay' => 'Gambang',
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        Location::create([
            'location_id' => '3',
            'barangay' => 'Dalipey',
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        Location::create([
            'location_id' => '4',
            'barangay' => 'Poblacio',
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        //Commodities
        Commodity::create([
            'commodity_id' => '1',
            'commodity_name' => 'Potato',
        ]);
        Commodity::create([
            'commodity_id' => '2',
            'commodity_name' => 'Cabbage',
        ]);
        Commodity::create([
            'commodity_id' => '3',
            'commodity_name' => 'Carrot',
        ]);
        Commodity::create([
            'commodity_id' => '4',
            'commodity_name' => 'Onion',
        ]);
        Commodity::create([
            'commodity_id' => '5',
            'commodity_name' => 'Broccoli',
        ]);
        Commodity::create([
            'commodity_id' => '6',
            'commodity_name' => 'Spinach',
        ]);
        Commodity::create([
            'commodity_id' => '7',
            'commodity_name' => 'Eggplant',
        ]);
        Commodity::create([
            'commodity_id' => '8',
            'commodity_name' => 'Beetroot',
        ]);
        Commodity::create([
            'commodity_id' => '9',
            'commodity_name' => 'Garlic',
        ]);
        Commodity::create([
            'commodity_id' => '10',
            'commodity_name' => 'Beans',
        ]);
        
        //User
        User::create([
            'id' => '1',
            'staff_id' => '1',
            'username' => 'Lexer',
            'password' => Hash::make('123'),
            'type' => 1,
        ]);
        User::create([
            'id' => '2',
            'staff_id' => '2',
            'username' => 'Kimjo',
            'password' => Hash::make('123'),
            'type' => 1,
        ]);
        User::create([
            'id' => '3',
            'staff_id' => '3',
            'username' => 'Trixan',
            'password' => Hash::make('123'),
            'type' => 1,
        ]);

    }
}
