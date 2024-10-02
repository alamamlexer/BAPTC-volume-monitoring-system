<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
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
