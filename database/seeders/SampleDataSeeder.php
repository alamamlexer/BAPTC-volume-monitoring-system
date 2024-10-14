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
use App\Models\LocationVehicle;
use App\Models\Transaction;

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
        
        VehicleType::create([
            'vehicle_type_id' => '9',
            'vehicle_type_name' => 'Pick-Up',
        ]);
        
        VehicleType::create([
            'vehicle_type_id' => '10',
            'vehicle_type_name' => 'Van',
        ]);
        
        VehicleType::create([
            'vehicle_type_id' => '14',
            'vehicle_type_name' => 'Dump Truck',
        ]);
        
        VehicleType::create([
            'vehicle_type_id' => '16',
            'vehicle_type_name' => 'Trailer Truck',
        ]);
        
        VehicleType::create([
            'vehicle_type_id' => '17',
            'vehicle_type_name' => 'Cargo Truck',
        ]);
        
        //Vehicle
        Vehicle::create([
            'vehicle_id' => '1',
            'plate_number' => 'AAA 111',
            'vehicle_name' => 'Farmer 1',
            'vehicle_type_id' => '1', // Single Tire
        ]);
        
        Vehicle::create([
            'vehicle_id' => '2',
            'plate_number' => 'AAA 112',
            'vehicle_name' => 'Farmer 2',
            'vehicle_type_id' => '2', // Double Tire
        ]);
        
        Vehicle::create([
            'vehicle_id' => '3',
            'plate_number' => 'AAA 113',
            'vehicle_name' => 'Farmer 3',
            'vehicle_type_id' => '3', // Forward
        ]);
        
        Vehicle::create([
            'vehicle_id' => '4',
            'plate_number' => 'AAA 114',
            'vehicle_name' => 'Farmer 4',
            'vehicle_type_id' => '1', // Single Tire
        ]);
        
        Vehicle::create([
            'vehicle_id' => '5',
            'plate_number' => 'BBB 115',
            'vehicle_name' => 'Green Hauler',
            'vehicle_type_id' => '4', // 10 Wheeler
        ]);
        
        Vehicle::create([
            'vehicle_id' => '6',
            'plate_number' => 'BBB 116',
            'vehicle_name' => 'Harvest Express',
            'vehicle_type_id' => '9', // Pick-Up
        ]);
        
        Vehicle::create([
            'vehicle_id' => '7',
            'plate_number' => 'CCC 117',
            'vehicle_name' => 'Fresh Transport',
            'vehicle_type_id' => '10', // Van
        ]);
        
        Vehicle::create([
            'vehicle_id' => '8',
            'plate_number' => 'CCC 118',
            'vehicle_name' => 'Farm Fleet',
            'vehicle_type_id' => '3', // Forward
        ]);
        
        Vehicle::create([
            'vehicle_id' => '9',
            'plate_number' => 'DDD 119',
            'vehicle_name' => 'Produce Carrier',
            'vehicle_type_id' => '2', // Double Tire
        ]);
        
        Vehicle::create([
            'vehicle_id' => '10',
            'plate_number' => 'DDD 120',
            'vehicle_name' => 'AgriMover',
            'vehicle_type_id' => '16', // Trailer Truck
        ]);
        
        Vehicle::create([
            'vehicle_id' => '11',
            'plate_number' => 'EEE 121',
            'vehicle_name' => 'Veggie Freight',
            'vehicle_type_id' => '17', // Cargo Truck
        ]);
        
        Vehicle::create([
            'vehicle_id' => '12',
            'plate_number' => 'EEE 122',
            'vehicle_name' => 'Farmland Cargo',
            'vehicle_type_id' => '4', // 10 Wheeler
        ]);
        
        Vehicle::create([
            'vehicle_id' => '13',
            'plate_number' => 'FFF 123',
            'vehicle_name' => 'Agri Haul',
            'vehicle_type_id' => '1', // Single Tire
        ]);
        
        Vehicle::create([
            'vehicle_id' => '14',
            'plate_number' => 'FFF 124',
            'vehicle_name' => 'Crop Runner',
            'vehicle_type_id' => '9', // Pick-Up
        ]);
        
        Vehicle::create([
            'vehicle_id' => '15',
            'plate_number' => 'GGG 125',
            'vehicle_name' => 'Farmway Transport',
            'vehicle_type_id' => '10', // Van
        ]);
        
        Vehicle::create([
            'vehicle_id' => '16',
            'plate_number' => 'GGG 126',
            'vehicle_name' => 'Harvest Truck',
            'vehicle_type_id' => '17', // Cargo Truck
        ]);
        
        Vehicle::create([
            'vehicle_id' => '17',
            'plate_number' => 'HHH 127',
            'vehicle_name' => 'Market Mover',
            'vehicle_type_id' => '2', // Double Tire
        ]);
        
        Vehicle::create([
            'vehicle_id' => '18',
            'plate_number' => 'HHH 128',
            'vehicle_name' => 'Farm Transporter',
            'vehicle_type_id' => '16', // Trailer Truck
        ]);
        
        Vehicle::create([
            'vehicle_id' => '19',
            'plate_number' => 'III 129',
            'vehicle_name' => 'Rural Hauler',
            'vehicle_type_id' => '14', // Dump Truck
        ]);
        
        Vehicle::create([
            'vehicle_id' => '20',
            'plate_number' => 'III 130',
            'vehicle_name' => 'Vegetable Express',
            'vehicle_type_id' => '9', // Pick-Up
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
            'barangay' => 'Poblacion',
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '5',
            'barangay' => 'Poblacion',
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '6',
            'barangay' => 'Loacan',
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '7',
            'barangay' => 'Tuding',
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '8',
            'barangay' => 'Ansagan',
            'municipality' => 'Tuba',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '9',
            'barangay' => 'Camp 4',
            'municipality' => 'Tuba',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '10',
            'barangay' => 'Padcal',
            'municipality' => 'Tuba',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '11',
            'barangay' => 'Longlong',
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '12',
            'barangay' => 'Pico',
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '13',
            'barangay' => 'Betag',
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '14',
            'barangay' => 'Lubas',
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '15',
            'barangay' => 'Ambiong',
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '16',
            'barangay' => 'Santo Tomas Proper',
            'municipality' => 'Baguio City',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '17',
            'barangay' => 'Irisan',
            'municipality' => 'Baguio City',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '18',
            'barangay' => 'Guisad Surong',
            'municipality' => 'Baguio City',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '19',
            'barangay' => 'Asin',
            'municipality' => 'Tuba',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Location::create([
            'location_id' => '20',
            'barangay' => 'Puguis',
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        
        //Location Vehicle
        LocationVehicle::create([
            'id' => '1',
            'vehicle_id' => '1',
            'location_id' => '1',
        ]);
        
        LocationVehicle::create([
            'id' => '2',
            'vehicle_id' => '1',
            'location_id' => '5',
        ]);
        
        LocationVehicle::create([
            'id' => '3',
            'vehicle_id' => '2',
            'location_id' => '2',
        ]);
        
        LocationVehicle::create([
            'id' => '4',
            'vehicle_id' => '2',
            'location_id' => '8',
        ]);
        
        LocationVehicle::create([
            'id' => '5',
            'vehicle_id' => '3',
            'location_id' => '3',
        ]);
        
        LocationVehicle::create([
            'id' => '6',
            'vehicle_id' => '3',
            'location_id' => '9',
        ]);
        
        LocationVehicle::create([
            'id' => '7',
            'vehicle_id' => '4',
            'location_id' => '4',
        ]);
        
        LocationVehicle::create([
            'id' => '8',
            'vehicle_id' => '5',
            'location_id' => '10',
        ]);
        
        LocationVehicle::create([
            'id' => '9',
            'vehicle_id' => '6',
            'location_id' => '6',
        ]);
        
        LocationVehicle::create([
            'id' => '10',
            'vehicle_id' => '7',
            'location_id' => '11',
        ]);
        
        LocationVehicle::create([
            'id' => '11',
            'vehicle_id' => '8',
            'location_id' => '7',
        ]);
        
        LocationVehicle::create([
            'id' => '12',
            'vehicle_id' => '9',
            'location_id' => '12',
        ]);
        
        LocationVehicle::create([
            'id' => '13',
            'vehicle_id' => '10',
            'location_id' => '13',
        ]);
        
        LocationVehicle::create([
            'id' => '14',
            'vehicle_id' => '11',
            'location_id' => '14',
        ]);
        
        LocationVehicle::create([
            'id' => '15',
            'vehicle_id' => '12',
            'location_id' => '15',
        ]);
        
        LocationVehicle::create([
            'id' => '16',
            'vehicle_id' => '13',
            'location_id' => '16',
        ]);
        
        LocationVehicle::create([
            'id' => '17',
            'vehicle_id' => '14',
            'location_id' => '17',
        ]);
        
        LocationVehicle::create([
            'id' => '18',
            'vehicle_id' => '15',
            'location_id' => '18',
        ]);
        
        LocationVehicle::create([
            'id' => '19',
            'vehicle_id' => '16',
            'location_id' => '19',
        ]);
        
        LocationVehicle::create([
            'id' => '20',
            'vehicle_id' => '17',
            'location_id' => '20',
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

        //Transaction
        Transaction::create([
            'date' => '2024-10-01',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '1',
            'volume' => '1500',
            'plate_number' => 'AAA 111',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 1',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-02',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '2000',
            'plate_number' => 'AAA 112',
            'vehicle_type_id' => '2',
            'name' => 'Farmer 2',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-03',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '3',
            'volume' => '1800',
            'plate_number' => 'AAA 113',
            'vehicle_type_id' => '3',
            'name' => 'Farmer 3',
            'barangay' => 'Dalipey',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-04',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '4',
            'volume' => '2300',
            'plate_number' => 'AAA 114',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 4',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-05',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '5',
            'volume' => '1200',
            'plate_number' => 'BBB 115',
            'vehicle_type_id' => '4',
            'name' => 'Green Hauler',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-06',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '6',
            'volume' => '1700',
            'plate_number' => 'BBB 116',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-07',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '7',
            'volume' => '1500',
            'plate_number' => 'CCC 117',
            'vehicle_type_id' => '10',
            'name' => 'Fresh Transport',
            'barangay' => 'Longlong',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-08',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '8',
            'volume' => '1400',
            'plate_number' => 'CCC 118',
            'vehicle_type_id' => '3',
            'name' => 'Farm Fleet',
            'barangay' => 'Tuding',  // Correct tied location
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-09',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '9',
            'volume' => '1600',
            'plate_number' => 'DDD 119',
            'vehicle_type_id' => '2',
            'name' => 'Produce Carrier',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-10',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '10',
            'volume' => '2100',
            'plate_number' => 'DDD 120',
            'vehicle_type_id' => '16',
            'name' => 'AgriMover',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);

        Transaction::create([
            'date' => '2024-10-11',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '1',
            'volume' => '2200',
            'plate_number' => 'EEE 121',
            'vehicle_type_id' => '4',
            'name' => 'Green Hauler',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-12',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '1950',
            'plate_number' => 'FFF 122',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-13',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '3',
            'volume' => '1850',
            'plate_number' => 'FFF 123',
            'vehicle_type_id' => '10',
            'name' => 'Fresh Transport',
            'barangay' => 'Longlong',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-14',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '4',
            'volume' => '1750',
            'plate_number' => 'GGG 124',
            'vehicle_type_id' => '3',
            'name' => 'Farm Fleet',
            'barangay' => 'Tuding',  // Correct tied location
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-15',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '5',
            'volume' => '2200',
            'plate_number' => 'GGG 125',
            'vehicle_type_id' => '16',
            'name' => 'AgriMover',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-16',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '6',
            'volume' => '1700',
            'plate_number' => 'HHH 126',
            'vehicle_type_id' => '17',
            'name' => 'Produce Carrier',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-17',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '7',
            'volume' => '1600',
            'plate_number' => 'HHH 127',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 1',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-18',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '8',
            'volume' => '1550',
            'plate_number' => 'III 128',
            'vehicle_type_id' => '4',
            'name' => 'Farmer 4',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-19',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '9',
            'volume' => '2000',
            'plate_number' => 'JJJ 129',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-20',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading inflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '2100',
            'plate_number' => 'KKK 130',
            'vehicle_type_id' => '2',
            'name' => 'Farmer 2',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);

        Transaction::create([
            'date' => '2024-10-01',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '1',
            'volume' => '1500',
            'plate_number' => 'AAA 111',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 1',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-02',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '2000',
            'plate_number' => 'AAA 112',
            'vehicle_type_id' => '2',
            'name' => 'Farmer 2',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-03',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '3',
            'volume' => '1800',
            'plate_number' => 'AAA 113',
            'vehicle_type_id' => '3',
            'name' => 'Farmer 3',
            'barangay' => 'Dalipey',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-04',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '4',
            'volume' => '2300',
            'plate_number' => 'AAA 114',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 4',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-05',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '5',
            'volume' => '1200',
            'plate_number' => 'BBB 115',
            'vehicle_type_id' => '4',
            'name' => 'Green Hauler',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-06',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '6',
            'volume' => '1700',
            'plate_number' => 'BBB 116',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-07',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '7',
            'volume' => '1500',
            'plate_number' => 'CCC 117',
            'vehicle_type_id' => '10',
            'name' => 'Fresh Transport',
            'barangay' => 'Longlong',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-08',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '8',
            'volume' => '1400',
            'plate_number' => 'CCC 118',
            'vehicle_type_id' => '3',
            'name' => 'Farm Fleet',
            'barangay' => 'Tuding',  // Correct tied location
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-09',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '9',
            'volume' => '1600',
            'plate_number' => 'DDD 119',
            'vehicle_type_id' => '2',
            'name' => 'Produce Carrier',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-10',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '10',
            'volume' => '2100',
            'plate_number' => 'DDD 120',
            'vehicle_type_id' => '16',
            'name' => 'AgriMover',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);

        Transaction::create([
            'date' => '2024-10-11',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '1',
            'volume' => '2200',
            'plate_number' => 'EEE 121',
            'vehicle_type_id' => '4',
            'name' => 'Green Hauler',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-12',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '1950',
            'plate_number' => 'FFF 122',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-13',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '3',
            'volume' => '1850',
            'plate_number' => 'FFF 123',
            'vehicle_type_id' => '10',
            'name' => 'Fresh Transport',
            'barangay' => 'Longlong',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-14',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '4',
            'volume' => '1750',
            'plate_number' => 'GGG 124',
            'vehicle_type_id' => '3',
            'name' => 'Farm Fleet',
            'barangay' => 'Tuding',  // Correct tied location
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-15',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '5',
            'volume' => '2200',
            'plate_number' => 'GGG 125',
            'vehicle_type_id' => '16',
            'name' => 'AgriMover',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-16',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '6',
            'volume' => '1700',
            'plate_number' => 'HHH 126',
            'vehicle_type_id' => '17',
            'name' => 'Produce Carrier',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-17',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '7',
            'volume' => '1600',
            'plate_number' => 'HHH 127',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 1',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-18',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '8',
            'volume' => '1550',
            'plate_number' => 'III 128',
            'vehicle_type_id' => '4',
            'name' => 'Farmer 4',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-19',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '9',
            'volume' => '2000',
            'plate_number' => 'JJJ 129',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-20',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'trading outflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '2100',
            'plate_number' => 'KKK 130',
            'vehicle_type_id' => '2',
            'name' => 'Farmer 2',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);

        Transaction::create([
            'date' => '2024-10-01',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '1',
            'volume' => '1500',
            'plate_number' => 'AAA 111',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 1',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-02',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '2000',
            'plate_number' => 'AAA 112',
            'vehicle_type_id' => '2',
            'name' => 'Farmer 2',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-03',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '3',
            'volume' => '1800',
            'plate_number' => 'AAA 113',
            'vehicle_type_id' => '3',
            'name' => 'Farmer 3',
            'barangay' => 'Dalipey',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-04',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '4',
            'volume' => '2300',
            'plate_number' => 'AAA 114',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 4',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-05',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '5',
            'volume' => '1200',
            'plate_number' => 'BBB 115',
            'vehicle_type_id' => '4',
            'name' => 'Green Hauler',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-06',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '6',
            'volume' => '1700',
            'plate_number' => 'BBB 116',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-07',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '7',
            'volume' => '1500',
            'plate_number' => 'CCC 117',
            'vehicle_type_id' => '10',
            'name' => 'Fresh Transport',
            'barangay' => 'Longlong',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-08',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '8',
            'volume' => '1400',
            'plate_number' => 'CCC 118',
            'vehicle_type_id' => '3',
            'name' => 'Farm Fleet',
            'barangay' => 'Tuding',  // Correct tied location
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-09',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '9',
            'volume' => '1600',
            'plate_number' => 'DDD 119',
            'vehicle_type_id' => '2',
            'name' => 'Produce Carrier',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-10',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '10',
            'volume' => '2100',
            'plate_number' => 'DDD 120',
            'vehicle_type_id' => '16',
            'name' => 'AgriMover',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);

        Transaction::create([
            'date' => '2024-10-11',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '1',
            'volume' => '2200',
            'plate_number' => 'EEE 121',
            'vehicle_type_id' => '4',
            'name' => 'Green Hauler',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-12',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '1950',
            'plate_number' => 'FFF 122',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-13',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '3',
            'volume' => '1850',
            'plate_number' => 'FFF 123',
            'vehicle_type_id' => '10',
            'name' => 'Fresh Transport',
            'barangay' => 'Longlong',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-14',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '4',
            'volume' => '1750',
            'plate_number' => 'GGG 124',
            'vehicle_type_id' => '3',
            'name' => 'Farm Fleet',
            'barangay' => 'Tuding',  // Correct tied location
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-15',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '5',
            'volume' => '2200',
            'plate_number' => 'GGG 125',
            'vehicle_type_id' => '16',
            'name' => 'AgriMover',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-16',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '6',
            'volume' => '1700',
            'plate_number' => 'HHH 126',
            'vehicle_type_id' => '17',
            'name' => 'Produce Carrier',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-17',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '7',
            'volume' => '1600',
            'plate_number' => 'HHH 127',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 1',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-18',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '8',
            'volume' => '1550',
            'plate_number' => 'III 128',
            'vehicle_type_id' => '4',
            'name' => 'Farmer 4',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-19',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '9',
            'volume' => '2000',
            'plate_number' => 'JJJ 129',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-20',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip inflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '2100',
            'plate_number' => 'KKK 130',
            'vehicle_type_id' => '2',
            'name' => 'Farmer 2',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);

        Transaction::create([
            'date' => '2024-10-01',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '1',
            'volume' => '1500',
            'plate_number' => 'AAA 111',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 1',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-02',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '2000',
            'plate_number' => 'AAA 112',
            'vehicle_type_id' => '2',
            'name' => 'Farmer 2',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-03',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '3',
            'volume' => '1800',
            'plate_number' => 'AAA 113',
            'vehicle_type_id' => '3',
            'name' => 'Farmer 3',
            'barangay' => 'Dalipey',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-04',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '4',
            'volume' => '2300',
            'plate_number' => 'AAA 114',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 4',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-05',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '5',
            'volume' => '1200',
            'plate_number' => 'BBB 115',
            'vehicle_type_id' => '4',
            'name' => 'Green Hauler',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-06',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '6',
            'volume' => '1700',
            'plate_number' => 'BBB 116',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-07',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '7',
            'volume' => '1500',
            'plate_number' => 'CCC 117',
            'vehicle_type_id' => '10',
            'name' => 'Fresh Transport',
            'barangay' => 'Longlong',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-08',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '8',
            'volume' => '1400',
            'plate_number' => 'CCC 118',
            'vehicle_type_id' => '3',
            'name' => 'Farm Fleet',
            'barangay' => 'Tuding',  // Correct tied location
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-09',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '9',
            'volume' => '1600',
            'plate_number' => 'DDD 119',
            'vehicle_type_id' => '2',
            'name' => 'Produce Carrier',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-10',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '10',
            'volume' => '2100',
            'plate_number' => 'DDD 120',
            'vehicle_type_id' => '16',
            'name' => 'AgriMover',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);

        Transaction::create([
            'date' => '2024-10-11',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '1',
            'volume' => '2200',
            'plate_number' => 'EEE 121',
            'vehicle_type_id' => '4',
            'name' => 'Green Hauler',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-12',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '1950',
            'plate_number' => 'FFF 122',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-13',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '3',
            'volume' => '1850',
            'plate_number' => 'FFF 123',
            'vehicle_type_id' => '10',
            'name' => 'Fresh Transport',
            'barangay' => 'Longlong',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-14',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '4',
            'volume' => '1750',
            'plate_number' => 'GGG 124',
            'vehicle_type_id' => '3',
            'name' => 'Farm Fleet',
            'barangay' => 'Tuding',  // Correct tied location
            'municipality' => 'Itogon',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-15',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '5',
            'volume' => '2200',
            'plate_number' => 'GGG 125',
            'vehicle_type_id' => '16',
            'name' => 'AgriMover',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-16',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '6',
            'volume' => '1700',
            'plate_number' => 'HHH 126',
            'vehicle_type_id' => '17',
            'name' => 'Produce Carrier',
            'barangay' => 'Gambang',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-17',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '7',
            'volume' => '1600',
            'plate_number' => 'HHH 127',
            'vehicle_type_id' => '1',
            'name' => 'Farmer 1',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-18',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '8',
            'volume' => '1550',
            'plate_number' => 'III 128',
            'vehicle_type_id' => '4',
            'name' => 'Farmer 4',
            'barangay' => 'Ampusongan',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-19',
            'time' => 'AM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '9',
            'volume' => '2000',
            'plate_number' => 'JJJ 129',
            'vehicle_type_id' => '9',
            'name' => 'Harvest Express',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'La Trinidad',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);
        
        Transaction::create([
            'date' => '2024-10-20',
            'time' => 'PM',
            'transaction_status' => 'regular',
            'transaction_type' => 'short trip outflow',
            'staff_id' => '1',
            'commodity_id' => '2',
            'volume' => '2100',
            'plate_number' => 'KKK 130',
            'vehicle_type_id' => '2',
            'name' => 'Farmer 2',
            'barangay' => 'Poblacion',  // Correct tied location
            'municipality' => 'Bakun',
            'province' => 'Benguet',
            'region' => 'CAR',
        ]);

        
        
     
        
    

    }
}
