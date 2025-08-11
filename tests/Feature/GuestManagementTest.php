<?php

use App\Models\Guest;
use App\Models\User;

beforeEach(function () {
    $this->staff = User::factory()->create(['role' => 'front_office']);
    $this->actingAs($this->staff);
});

test('staff can view the guest list page', function () {
    $guest = Guest::factory()->create();

    $this->get(route('guests.index'))
        ->assertOk()
        ->assertSeeText('Manajemen Data Tamu')
        ->assertSeeText($guest->name);
});

test('staff can create a new guest', function () {
    $guestData = [
        'name' => 'Tamu Baru',
        'id_type' => 'KTP',
        'id_number' => '1234567890123456',
        'phone_number' => '08123456789',
    ];

    $this->post(route('guests.store'), $guestData);

    $this->assertDatabaseHas('guests', [
        'id_number' => '1234567890123456',
    ]);
});

test('staff cannot create a guest with a duplicate id number', function () {
    Guest::factory()->create(['id_number' => '111222333444']);

    $duplicateGuestData = [
        'name' => 'Tamu Duplikat',
        'id_type' => 'KTP',
        'id_number' => '111222333444',
    ];

    $response = $this->post(route('guests.store'), $duplicateGuestData);

    $response->assertSessionHasErrors('id_number');
    $this->assertDatabaseCount('guests', 1);
});

test('staff can update a guest', function () {
    $guest = Guest::factory()->create();

    $updatedData = [
        'name' => 'Nama Sudah Diubah',
        'id_type' => 'Paspor',
        'id_number' => $guest->id_number,
    ];

    $this->put(route('guests.update', $guest), $updatedData);

    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
        'name' => 'Nama Sudah Diubah',
        'id_type' => 'Paspor',
    ]);
});

test('staff can delete a guest', function () {
    $guest = Guest::factory()->create();

    $this->delete(route('guests.destroy', $guest));

    $this->assertDatabaseMissing('guests', [
        'id' => $guest->id,
    ]);
});
