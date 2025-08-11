<?php

use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($this->admin);
    $this->staff = User::factory()->create(['role' => 'front_office']);
});

test('admin can create a new room type', function () {
    $roomTypeData = [
        'name' => 'Deluxe King',
        'price_per_night' => 1200000,
        'description' => 'Kamar dengan kasur King Size.',
    ];

    $this->post(route('room-types.store'), $roomTypeData);

    $this->assertDatabaseHas('room_types', ['name' => 'Deluxe King']);
});

test('admin can update a room type', function () {
    $roomType = RoomType::factory()->create();

    $this->put(route('room-types.update', $roomType), [
        'name' => 'Deluxe Twin',
        'price_per_night' => $roomType->price_per_night,
    ]);

    $this->assertDatabaseHas('room_types', ['id' => $roomType->id, 'name' => 'Deluxe Twin']);
});

test('non-admin user cannot access room type management', function () {
    $this->actingAs($this->staff)
        ->get(route('room-types.index'))
        ->assertForbidden();
});

test('admin can create a new room', function () {
    $roomType = RoomType::factory()->create();
    $roomData = [
        'room_type_id' => $roomType->id,
        'room_number' => '201',
        'status' => 'available',
    ];

    $this->post(route('rooms.store'), $roomData);

    $this->assertDatabaseHas('rooms', ['room_number' => '201']);
});

test('admin can delete a room', function () {
    $roomType = RoomType::factory()->create();
    $room = Room::factory()->create(['room_type_id' => $roomType->id]);

    $this->delete(route('rooms.destroy', $room));

    $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
});

test('validation fails when creating a room with a duplicate number', function () {
    $roomType = RoomType::factory()->create();
    Room::factory()->create(['room_number' => '202', 'room_type_id' => $roomType->id]);

    $duplicateRoomData = [
        'room_type_id' => $roomType->id,
        'room_number' => '202',
        'status' => 'available',
    ];

    $this->post(route('rooms.store'), $duplicateRoomData)
        ->assertSessionHasErrors('room_number');

    $this->assertDatabaseCount('rooms', 1);
});
