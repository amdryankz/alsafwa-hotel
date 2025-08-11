<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('guest cannot access user management page', function () {
    $response = $this->get('/users');

    $response->assertRedirect('/login');
});

test('non-admin user cannot access user management page', function () {
    $user = User::factory()->create([
        'role' => 'front_office',
    ]);

    $this->actingAs($user);

    $response = $this->get('/users');

    $response->assertForbidden();
});

test('admin can access user management page', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $otherUser = User::factory()->create();

    $this->actingAs($admin);

    $response = $this->get('/users');

    $response->assertOk();
    $response->assertSeeText('Manajemen Akun Karyawan');
    $response->assertSeeText($otherUser->name);
});

test('admin can create a new user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $userData = [
        'name' => 'Budi Staf',
        'email' => 'budi@hotel.com',
        'role' => 'front_office',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->actingAs($admin)->post('/users', $userData);

    $response->assertRedirect('/users');

    $this->assertDatabaseHas('users', [
        'email' => 'budi@hotel.com',
        'role' => 'front_office',
    ]);

    $newUser = User::where('email', 'budi@hotel.com')->first();
    $this->assertTrue(Hash::check('password123', $newUser->password));
});

test('validation fails if user creation data is incomplete', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $userData = [
        'name' => '',
        'email' => 'budi@hotel.com',
        'role' => 'front_office',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->actingAs($admin)->post('/users', $userData);

    $response->assertSessionHasErrors('name');

    $this->assertDatabaseMissing('users', [
        'email' => 'budi@hotel.com',
    ]);
});

test('non-admin user cannot create a new user', function () {
    $user = User::factory()->create(['role' => 'front_office']);
    $userData = [
        'name' => 'Budi Staf',
        'email' => 'budi@hotel.com',
        'role' => 'front_office',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->actingAs($user)->post('/users', $userData);

    $response->assertForbidden();
    $this->assertDatabaseMissing('users', [
        'email' => 'budi@hotel.com',
    ]);
});

test('admin can update a user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $userToUpdate = User::factory()->create(['role' => 'front_office']);
    $updatedData = [
        'name' => 'Budi Diperbarui',
        'email' => 'budi.baru@hotel.com',
        'role' => 'accountant',
    ];

    $response = $this->actingAs($admin)->put('/users/'.$userToUpdate->id, $updatedData);

    $response->assertRedirect('/users');
    $this->assertDatabaseHas('users', [
        'id' => $userToUpdate->id,
        'name' => 'Budi Diperbarui',
        'role' => 'accountant',
    ]);
});

test('admin can delete another user', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $userToDelete = User::factory()->create();

    $response = $this->actingAs($admin)->delete('/users/'.$userToDelete->id);

    $response->assertRedirect('/users');
    $this->assertDatabaseMissing('users', [
        'id' => $userToDelete->id,
    ]);
});
