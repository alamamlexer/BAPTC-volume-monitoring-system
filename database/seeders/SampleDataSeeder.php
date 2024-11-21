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
use App\Models\FacilitatorLocationVehicle;
use App\Models\Facilitator;
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
            'staff_name' => 'admin',
            'email' => 'admin@gmail.com',
            'contact_number' => '0999999994',
        ]); 
        Staff::create([
            'staff_id' => '2',
            'staff_name' => 'Unknown',
            'email' => 'baptc.2015@gmail.com',
            'contact_number' => '0999999999',
        ]);
        Staff::create([
            'staff_id' => '3',
            'staff_name' => 'Kimjo',
            'email' => 'kimjo@gmail.com',
            'contact_number' => '0999999992',
        ]);
        Staff::create([
            'staff_id' => '4',
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
        
        //Facilitator
        Facilitator::create([
            'facilitator_id' => 1,
            'facilitator_code' => 'A1',
            'facilitator_name' => 'Vegetable Express',
        ]);
        
        Facilitator::create([
            'facilitator_id' => 2,
            'facilitator_code' => 'B2',
            'facilitator_name' => 'Fruit Hub',
        ]);
        
        Facilitator::create([
            'facilitator_id' => 3,
            'facilitator_code' => 'C3',
            'facilitator_name' => 'Meat Masters',
        ]);
        
        Facilitator::create([
            'facilitator_id' => 4,
            'facilitator_code' => 'D4',
            'facilitator_name' => 'Dairy Delight',
        ]);
        
        Facilitator::create([
            'facilitator_id' => 5,
            'facilitator_code' => 'E5',
            'facilitator_name' => 'Grain Works',
        ]);
        
        Facilitator::create([
            'facilitator_id' => 6,
            'facilitator_code' => 'F6',
            'facilitator_name' => 'Spice Traders',
        ]);
        
        Facilitator::create([
            'facilitator_id' => 7,
            'facilitator_code' => 'G7',
            'facilitator_name' => 'Seafood Select',
        ]);
        
        Facilitator::create([
            'facilitator_id' => 8,
            'facilitator_code' => 'H8',
            'facilitator_name' => 'Bakery Bliss',
        ]);
        
        Facilitator::create([
            'facilitator_id' => 9,
            'facilitator_code' => 'I9',
            'facilitator_name' => 'Beverage Bazaar',
        ]);
        
        Facilitator::create([
            'facilitator_id' => 10,
            'facilitator_code' => 'J10',
            'facilitator_name' => 'Frozen Feast',
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
        
        //Facilitator Location Vehicle
        FacilitatorLocationVehicle::create([
            'id' => 1,
            'vehicle_id' => 1,
            'location_id' => 2,
            'facilitator_id' => 3,
        ]);
        
        FacilitatorLocationVehicle::create([
            'id' => 2,
            'vehicle_id' => 4,
            'location_id' => 1,
            'facilitator_id' => 5,
        ]);
        
        FacilitatorLocationVehicle::create([
            'id' => 3,
            'vehicle_id' => 3,
            'location_id' => 4,
            'facilitator_id' => 2,
        ]);
        
        FacilitatorLocationVehicle::create([
            'id' => 4,
            'vehicle_id' => 2,
            'location_id' => 3,
            'facilitator_id' => 4,
        ]);
        
        FacilitatorLocationVehicle::create([
            'id' => 5,
            'vehicle_id' => 5,
            'location_id' => 5,
            'facilitator_id' => 1,
        ]);
        
        FacilitatorLocationVehicle::create([
            'id' => 6,
            'vehicle_id' => 6,
            'location_id' => 7,
            'facilitator_id' => 8,
        ]);
        
        FacilitatorLocationVehicle::create([
            'id' => 7,
            'vehicle_id' => 7,
            'location_id' => 6,
            'facilitator_id' => 9,
        ]);
        
        FacilitatorLocationVehicle::create([
            'id' => 8,
            'vehicle_id' => 8,
            'location_id' => 9,
            'facilitator_id' => 7,
        ]);
        
        FacilitatorLocationVehicle::create([
            'id' => 9,
            'vehicle_id' => 9,
            'location_id' => 8,
            'facilitator_id' => 10,
        ]);
        
        FacilitatorLocationVehicle::create([
            'id' => 10,
            'vehicle_id' => 10,
            'location_id' => 10,
            'facilitator_id' => 6,
        ]);
        

       
        //Commodities
$commodities = [
    'Snap Beans',
    'Banana',
    'Basil',
    'Bell Pepper - California',
    'Bell Pepper - Chinese',
    'Bell Pepper - Donxing',
    'Broccoli',
    'Tomato',
    'Pineapple',
    'Cabbage - Luckyball',
    'Cabbage - Rareball',
    'Cabbage - Scorpio',
    'Camote Tops',
    'Polonchai',
    'Carrot',
    'Cauliflower',
    'Celery - Long',
    'Chayote',
    'Chayote Tops',
    'Chinese Cabbage',
    'Lettuce - Green Ice',
    'Cucumber',
    'Coconut',
    'Dragon Fruit',
    'Fennel',
    'Garden Pea - Chinese',
    'Garden Pea - Dwarf',
    'Garden Pea - Lapad',
    'Garden Pea - Plastic',
    'Radish - Korean',
    'Lemon - Green',
    'Lemon - Yellow',
    'Wansoy',
    'Lettuce - Deep Red',
    'Lettuce - Green Ice',
    'Lettuce - Iceberg',
    'Lettuce - Romaine',
    'Mandarin Orange',
    'Mint',
    'Mushroom - Button',
    'Mushroom - Oyster',
    'Mushroom - Shitake',
    'Onion Leeks',
    'Parsley',
    'Pechay',
    'Potato',
    'Cabbage - Mighty Ball',
    'Cabbage - Wonderball',
    'Bell Pepper - Dongxin',
    'Bell Pepper - Sultan',
    'Mustard',
    'Ginger',
    'Radish',
    'Bell Pepper',
    'Cabbage - Red',
    'Rice',
    'Rosemary',
    'Sari-Sari',
    'Spinach',
    'Squash',
    'Strawberry',
    'Sugar Beets',
    'Suha',
    'Sweet Potato',
    'Taro/Gabi',
    'Thyme',
    'Tomato',
    'Tomato - Cherry',
    'Tomato - Diamante',
    'Tomato - Marmimar',
    'Wansoy',
    'Water Cress',
    'Watermelon',
    'Yakun',
    'Young Corn',
    'Zuchinni',
];

// Loop through the array and create each commodity
foreach ($commodities as $commodity_name) {
    Commodity::create([
        'commodity_name' => $commodity_name,
    ]);
}
        
        //User
        User::create([
            'id' => 1,
            'staff_id' => 1,
            'username' => 'admin',
            'password' => Hash::make('baptc-volume'),
            'type' => 0, // admin
            'is_active' => true, // Admin should always be active
        ]);

        // Create Staff users (is_active will be set to false by default by the migration)
        User::create([
            'id' => 2,
            'staff_id' => 2,
            'email' => 'trixan@gmail.com',
            'username' => 'Lexer',
            'password' => Hash::make('123'),
            'type' => 1, // staff
            'is_active' => false, // Explicitly inactive
        ]);

        User::create([
            'id' => 3,
            'staff_id' => 3,
            'email' => 'kimjo@gmail.com',
            'username' => 'Kimjo',
            'password' => Hash::make('123'),
            'type' => 1, // staff
            'is_active' => false, // Explicitly inactive
        ]);

        User::create([
            'id' => 4,
            'staff_id' => 4,
            'email' => 'trixan@gmail.com',
            'username' => 'Trixan',
            'password' => Hash::make('123'),
            'type' => 1, // staff
            'is_active' => false, // Explicitly inactive
        ]);

      



    }
}
