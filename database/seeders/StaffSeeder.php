<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
