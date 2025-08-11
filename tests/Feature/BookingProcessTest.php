<?php

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->staff = User::factory()->create(['role' => 'front_office']);
    $this->roomType = RoomType::factory()->create(['name' => 'Deluxe', 'price_per_night' => 500000]);
    $this->room = Room::factory()->create(['room_type_id' => $this->roomType->id, 'status' => 'available', 'room_number' => 101]);
    $this->guest = Guest::factory()->create();
});

test('staff can create a reservation for an available room', function () {
    $reservationData = [
        'guest_id' => $this->guest->id,
        'check_in_date' => Carbon::parse('2025-08-15 14:00:00'),
        'check_out_date' => Carbon::parse('2025-08-17 12:00:00'),
        'room_ids' => [$this->room->id],
    ];

    $response = $this->actingAs($this->staff)->post(route('reservations.store'), $reservationData);

    $response->assertRedirect(route('bookings.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('bookings', [
        'guest_id' => $this->guest->id,
        'status' => 'booked',
    ]);

    $this->assertDatabaseHas('booking_room', [
        'room_id' => $this->room->id,
    ]);

    $this->assertDatabaseHas('rooms', [
        'id' => $this->room->id,
        'status' => 'available',
    ]);
});

test('system prevents double booking on overlapping dates', function () {
    $booking1 = Booking::factory()->create([
        'guest_id' => $this->guest->id,
        'check_in_date' => Carbon::parse('2025-08-15 14:00:00'),
        'check_out_date' => Carbon::parse('2025-08-17 12:00:00'),
        'status' => 'booked',
    ]);
    $booking1->rooms()->attach($this->room->id, ['price_at_booking' => 500000]);

    $overlappingReservationData = [
        'guest_id' => $this->guest->id,
        'check_in_date' => Carbon::parse('2025-08-16 14:00:00'),
        'check_out_date' => Carbon::parse('2025-08-18 12:00:00'),
        'room_ids' => [$this->room->id],
    ];

    $response = $this->actingAs($this->staff)->post(route('reservations.store'), $overlappingReservationData);

    $response->assertSessionHas('error');

    $this->assertDatabaseCount('bookings', 1);
});

test('staff can confirm a reservation and check in a guest', function () {
    $booking = Booking::factory()->create([
        'guest_id' => $this->guest->id,
        'check_in_date' => Carbon::parse('2025-08-15 14:00:00'),
        'check_out_date' => Carbon::parse('2025-08-17 12:00:00'),
        'status' => 'booked',
    ]);
    $booking->rooms()->attach($this->room->id, ['price_at_booking' => 500000]);

    $response = $this->actingAs($this->staff)
        ->post(route('bookings.confirmCheckIn', $booking));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'checked_in',
    ]);

    $this->assertDatabaseHas('rooms', [
        'id' => $this->room->id,
        'status' => 'occupied',
    ]);
});

test('system prevents checkout if bill is not fully paid', function () {
    $booking = Booking::factory()->create([
        'guest_id' => $this->guest->id,
        'check_in_date' => Carbon::parse('2025-08-15 14:00:00'),
        'check_out_date' => Carbon::parse('2025-08-17 12:00:00'),
        'status' => 'checked_in',
        'total_amount' => 1000000,
        'discount' => 0,
        'tax_percentage' => 0,
    ]);

    $response = $this->actingAs($this->staff)
        ->post(route('bookings.checkout', $booking));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'checked_in',
    ]);
});

test('staff can perform checkout if bill is fully paid', function () {
    $booking = Booking::factory()->create([
        'guest_id' => $this->guest->id,
        'check_in_date' => Carbon::parse('2025-08-15 14:00:00'),
        'check_out_date' => Carbon::parse('2025-08-17 12:00:00'),
        'status' => 'checked_in',
        'total_amount' => 1000000,
        'discount' => 100000,
        'tax_percentage' => 10,
    ]);
    $booking->rooms()->attach($this->room->id, ['price_at_booking' => 1000000]);
    $this->room->update(['status' => 'occupied']);

    $booking->payments()->create([
        'amount' => 990000,
        'payment_method' => 'cash',
        'payment_date' => now(),
    ]);

    $response = $this->actingAs($this->staff)
        ->post(route('bookings.checkout', $booking));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'checked_out',
    ]);

    $this->assertNotNull($booking->fresh()->check_out_date);

    $this->assertDatabaseHas('rooms', [
        'id' => $this->room->id,
        'status' => 'available',
    ]);
});

test('staff can change a guest to another available room', function () {
    $newAvailableRoom = Room::factory()->create(['room_type_id' => $this->roomType->id, 'status' => 'available', 'room_number' => 102]);

    $booking = Booking::factory()->create(['status' => 'checked_in']);
    $booking->rooms()->attach($this->room->id, ['price_at_booking' => 500000]);
    $this->room->update(['status' => 'occupied']);

    $response = $this->actingAs($this->staff)->post(route('bookings.changeRoom', $booking), [
        'old_room_id' => $this->room->id,
        'new_room_id' => $newAvailableRoom->id,
    ]);

    $response->assertSessionHas('success');

    $this->assertDatabaseHas('rooms', ['id' => $this->room->id, 'status' => 'available']);

    $this->assertDatabaseHas('rooms', ['id' => $newAvailableRoom->id, 'status' => 'occupied']);

    $this->assertDatabaseMissing('booking_room', ['booking_id' => $booking->id, 'room_id' => $this->room->id]);
    $this->assertDatabaseHas('booking_room', ['booking_id' => $booking->id, 'room_id' => $newAvailableRoom->id]);
});

test('system prevents changing to an unavailable room', function () {
    $newOccupiedRoom = Room::factory()->create(['room_type_id' => $this->roomType->id, 'status' => 'occupied', 'room_number' => 102]);

    $booking = Booking::factory()->create(['status' => 'checked_in']);
    $booking->rooms()->attach($this->room->id, ['price_at_booking' => 500000]);
    $this->room->update(['status' => 'occupied']);

    $response = $this->actingAs($this->staff)->post(route('bookings.changeRoom', $booking), [
        'old_room_id' => $this->room->id,
        'new_room_id' => $newOccupiedRoom->id,
    ]);

    $response->assertSessionHas('error');

    $this->assertDatabaseHas('rooms', ['id' => $this->room->id, 'status' => 'occupied']);
    $this->assertDatabaseHas('rooms', ['id' => $newOccupiedRoom->id, 'status' => 'occupied']);
});

test('staff can record a new payment for a booking', function () {
    $booking = Booking::factory()->create([
        'status' => 'checked_in',
        'total_amount' => 1000000,
    ]);

    $paymentData = [
        'amount' => 500000,
        'payment_method' => 'qris',
        'payment_date' => now()->toDateTimeString(),
    ];

    $response = $this->actingAs($this->staff)
        ->post(route('bookings.payments.store', $booking), $paymentData);

    $response->assertSessionHas('success');

    $this->assertDatabaseHas('payments', [
        'booking_id' => $booking->id,
        'amount' => 500000,
        'payment_method' => 'qris',
    ]);

    $this->assertEquals(500000, $booking->fresh()->paid_amount);
});

test('system prevents overpayment for a booking', function () {
    $booking = Booking::factory()->create([
        'status' => 'checked_in',
        'total_amount' => 1000000,
    ]);

    $paymentData = [
        'amount' => 1100000,
        'payment_method' => 'cash',
        'payment_date' => now()->toDateTimeString(),
    ];

    $response = $this->actingAs($this->staff)
        ->post(route('bookings.payments.store', $booking), $paymentData);

    $response->assertSessionHasErrors('amount');

    $this->assertDatabaseCount('payments', 0);
});
