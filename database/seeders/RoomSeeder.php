<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat Data Tipe Kamar (Room Types)
        $standardType = RoomType::create([
            'name' => 'Standard',
            'price_per_night' => 300000
        ]);

        $deluxeType = RoomType::create([
            'name' => 'Deluxe',
            'price_per_night' => 400000
        ]);

        $superiorType = RoomType::create([
            'name' => 'Superior',
            'price_per_night' => 350000
        ]);

        $this->command->info('Room types created successfully!');

        // 2. Membuat Data Kamar (Rooms) untuk setiap tipe
        for ($i = 1; $i <= 5; $i++) {
            Room::create([
                'room_type_id' => $standardType->id,
                'room_number' => '1' . str_pad($i, 2, '0', STR_PAD_LEFT), // Hasil: 101, 102, ... 110
                'status' => 'available',
            ]);
        }

        for ($i = 6; $i <= 9; $i++) {
            Room::create([
                'room_type_id' => $deluxeType->id,
                'room_number' => '1' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => 'available',
            ]);
        }

        Room::create([
            'room_type_id' => $superiorType->id,
            'room_number' => '110',
            'status' => 'available',
        ]);

        $this->command->info('Rooms created successfully!');;
    }
}
