<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Staff;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $table->id();
        // $table->unsignedBigInteger('staff_id')->nullable();
        // $table->unsignedBigInteger('farmer_id')->nullable();
        // $table->string('username')->unique();
        // $table->string('password');
        // $table->boolean('type')->default(false); //false=user,true=admin
         //User
        User::create([
            'id' => 5,
            'staff_id' => 5,
            'username' => 'TOCLOS',
            'email' => 'baptc.2015@gmail.com',
            'password' => Hash::make('123'),
            'type' => 1, // admin
            'is_active' => false, // Admin should always be active
        ]);
        
        User::create([
            'id' => 7,
            'staff_id' => 7,
            'username' => 'MARJOE SALAVARIA',
            'email' => 'baptc.2015@gmail.com',
            'password' => Hash::make('123'),
            'type' => 1, // admin
            'is_active' => false, // Admin should always be active
        ]);
        
        User::create([
            'id' => 9,
            'staff_id' => 9,
            'username' => 'MARLON SORIANO',
            'email' => 'baptc.2015@gmail.com',
            'password' => Hash::make('123'),
            'type' => 1, // admin
            'is_active' => false, // Admin should always be active
        ]);
         User::create([
            'id' => 111,
            'staff_id' => 10,
            'username' => 'LOUIE TOCLO',
            'email' => 'baptc.2015@gmail.com',
            'password' => Hash::make('123'),
            'type' => 1, // admin
            'is_active' => false, // Admin should always be active
        ]);
         User::create([
            'id' => 12,
            'staff_id' => 11,
            'username' => 'SAMUEL BESIC',
            'email' => 'baptc.2015@gmail.com',
            'password' => Hash::make('123'),
            'type' => 1, // admin
            'is_active' => false, // Admin should always be active
        ]);
         User::create([
            'id' => 13,
            'staff_id' => 12,
            'username' => 'GLENN MAR PABLO',
            'email' => 'baptc.2015@gmail.com',
            'password' => Hash::make('123'),
            'type' => 1, // admin
            'is_active' => false, // Admin should always be active
        ]);

         Staff::create([
            'staff_id' => '15',
            'staff_name' => 'GERALDINE BAUTISTA',
            'email' => 'baptc.2015@gmail.com',
            'contact_number' => '0999999991',
        ]); 
        User::create([
            'id' => 14,
            'staff_id' => 15,
            'username' => 'GERALDINE BAUTISTA',
            'email' => 'baptc.2015@gmail.com',
            'password' => Hash::make('123'),
            'type' => 1, // admin
            'is_active' => false, // Admin should always be active
        ]);
          Staff::create([
            'staff_id' => '16',
            'staff_name' => 'JORDAN SUBION',
            'email' => 'baptc.2015@gmail.com',
            'contact_number' => '0999999991',
        ]); 
        User::create([
            'id' => 15,
            'staff_id' => 16,
            'username' => 'JORDAN SUBION',
            'email' => 'baptc.2015@gmail.com',
            'password' => Hash::make('123'),
            'type' => 1, // admin
            'is_active' => false, // Admin should always be active
        ]);
         Staff::create([
            'staff_id' => '17',
            'staff_name' => 'SIXTO LAPNITEN',
            'email' => 'baptc.2015@gmail.com',
            'contact_number' => '0999999991',
        ]); 
        User::create([
            'id' => 16,
            'staff_id' => 17,
            'username' => 'SIXTO LAPNITEN',
            'email' => 'baptc.2015@gmail.com',
            'password' => Hash::make('123'),
            'type' => 1, // admin
            'is_active' => false, // Admin should always be active
        ]);
        

        

    }
}
