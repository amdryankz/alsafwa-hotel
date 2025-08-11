<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Owner User',
            'email' => 'owner@hotel.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Accountant User',
            'email' => 'akuntan@hotel.com',
            'password' => Hash::make('password'),
            'role' => 'accountant',
        ]);

        User::create([
            'name' => 'Front Office User',
            'email' => 'fo@hotel.com',
            'password' => Hash::make('password'),
            'role' => 'front_office',
        ]);
    }
}
