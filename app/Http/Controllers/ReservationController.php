<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function create()
    {
        $guests = Guest::orderBy('name')->get();
        $rooms = Room::with('roomType')->get();

        return view('reservations.create', compact('guests', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'exists:rooms,id',
        ]);

        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);

        $unavailableRooms = Room::whereIn('id', $validated['room_ids'])
            ->whereHas('bookings', function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<', $checkOut)
                        ->where('check_out_date', '>', $checkIn);
                })->whereIn('status', ['booked', 'checked_in']);
            })->get();

        if ($unavailableRooms->isNotEmpty()) {
            $roomNumbers = $unavailableRooms->pluck('room_number')->implode(', ');

            return back()->with('error', "Kamar #{$roomNumbers} tidak tersedia pada rentang tanggal yang dipilih.")->withInput();
        }

        DB::beginTransaction();
        try {
            $nights = $checkIn->startOfDay()->diffInDays($checkOut->startOfDay()) ?: 1;

            $booking = Booking::create([
                'guest_id' => $validated['guest_id'],
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'status' => 'booked',
            ]);

            $totalRoomPrice = 0;
            foreach ($validated['room_ids'] as $roomId) {
                $room = Room::with('roomType')->find($roomId);
                $totalRoomPrice += ($room->roomType->price_per_night * $nights);
                $booking->rooms()->attach($roomId, ['price_at_booking' => $room->roomType->price_per_night]);
            }

            $booking->update(['total_amount' => $totalRoomPrice]);
            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Reservasi berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }
}
