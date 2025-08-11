<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'active');

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($status == 'history') {
            $query = Booking::with(['guest', 'rooms'])
                ->where('status', 'checked_out');

            if ($startDate && $endDate) {
                $query->whereBetween('check_out_date', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay(),
                ]);
            }

            $bookings = $query->latest('check_out_date')->paginate(15);
            $pageTitle = 'Riwayat Transaksi';
        } else {
            $bookings = Booking::with(['guest', 'rooms'])
                ->whereIn('status', ['checked_in', 'booked'])
                ->latest()
                ->paginate(15);
            $pageTitle = 'Daftar Transaksi Aktif';
        }

        return view('bookings.index', [
            'bookings' => $bookings,
            'pageTitle' => $pageTitle,
            'currentStatus' => $status,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guests = Guest::orderBy('name')->get();
        $availableRooms = Room::with('roomType')->where('status', 'available')->get();

        return view('bookings.create', compact('guests', 'availableRooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'exists:rooms,id',
        ]);

        DB::beginTransaction();
        try {
            $checkInDate = Carbon::parse($validated['check_in_date']);
            $checkOutDate = Carbon::parse($validated['check_out_date']);
            $numberOfNights = $checkInDate->copy()->startOfDay()->diffInDays($checkOutDate->copy()->startOfDay());

            if ($numberOfNights <= 0) {
                $numberOfNights = 1;
            }

            $booking = Booking::create([
                'guest_id' => $validated['guest_id'],
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'status' => 'checked_in',
            ]);

            $totalRoomPrice = 0;

            foreach ($validated['room_ids'] as $roomId) {
                $room = Room::with('roomType')->find($roomId);

                if ($room->status !== 'available') {
                    throw new \Exception("Kamar #{$room->room_number} sudah tidak tersedia.");
                }

                $totalRoomPrice += ($room->roomType->price_per_night * $numberOfNights);

                $booking->rooms()->attach($roomId, ['price_at_booking' => $room->roomType->price_per_night]);
                $room->update(['status' => 'occupied']);
            }

            $booking->update(['total_amount' => $totalRoomPrice]);

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Check-in untuk '.$numberOfNights.' malam berhasil dilakukan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat check-in: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['guest', 'rooms.roomType', 'services', 'payments']);
        $nights = $this->calculateNights($booking->check_in_date, $booking->check_out_date);

        $availableRooms = Room::where('status', 'available')->with('roomType')->get();

        return view('bookings.show', compact('booking', 'nights', 'availableRooms'));
    }

    /**
     * Memproses checkout untuk sebuah booking.
     */
    public function checkout(Request $request, Booking $booking)
    {
        $balance = $booking->grand_total - $booking->paid_amount;
        if ($balance > 0) {
            return back()->with('error', 'Check-out gagal! Tagihan belum lunas. Sisa tagihan: Rp '.number_format($balance));
        }

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'checked_out',
                'check_out_date' => now(),
            ]);

            foreach ($booking->rooms as $room) {
                $room->update(['status' => 'available']);
            }

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Check-out berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Membatalkan sebuah reservasi.
     */
    public function cancel(Booking $booking)
    {
        if ($booking->status !== 'booked') {
            return back()->with('error', 'Hanya reservasi yang belum check-in yang bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.index')->with('success', 'Reservasi berhasil dibatalkan.');
    }

    /**
     * Menampilkan halaman invoice yang siap cetak.
     */
    public function print(Booking $booking)
    {
        $booking->load(['guest', 'rooms.roomType', 'services', 'payments']);
        $nights = $this->calculateNights($booking->check_in_date, $booking->check_out_date);

        return view('bookings.print', compact('booking', 'nights'));
    }

    private function calculateNights($checkIn, $checkOut)
    {
        $checkInDate = Carbon::parse($checkIn)->startOfDay();
        $checkOutDate = Carbon::parse($checkOut)->startOfDay();
        $nights = $checkInDate->diffInDays($checkOutDate);

        return ($nights <= 0) ? 1 : $nights;
    }

    public function changeRoom(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'old_room_id' => 'required|exists:rooms,id',
            'new_room_id' => 'required|exists:rooms,id|different:old_room_id',
        ]);

        if ($booking->status !== 'checked_in') {
            return back()->with('error', 'Tamu tidak dalam status check-in.');
        }

        $oldRoom = Room::find($validated['old_room_id']);
        $newRoom = Room::with('roomType')->find($validated['new_room_id']);

        if ($newRoom->status !== 'available') {
            return back()->with('error', "Kamar #{$newRoom->room_number} sudah tidak tersedia.");
        }

        DB::beginTransaction();
        try {
            $oldRoom->update(['status' => 'available']);

            $newRoom->update(['status' => 'occupied']);

            $booking->rooms()->detach($oldRoom->id);
            $booking->rooms()->attach($newRoom->id, ['price_at_booking' => $newRoom->roomType->price_per_night]);

            DB::commit();

            return back()->with('success', "Tamu berhasil dipindahkan dari kamar #{$oldRoom->room_number} ke #{$newRoom->room_number}.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat proses pindah kamar: '.$e->getMessage());
        }
    }

    public function confirmCheckIn(Booking $booking)
    {
        if ($booking->status !== 'booked') {
            return back()->with('error', 'Hanya reservasi yang bisa dikonfirmasi untuk check-in.');
        }

        DB::beginTransaction();
        try {
            $booking->update(['status' => 'checked_in']);

            foreach ($booking->rooms as $room) {
                $room->update(['status' => 'occupied']);
            }

            DB::commit();

            return back()->with('success', 'Tamu berhasil check-in.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
