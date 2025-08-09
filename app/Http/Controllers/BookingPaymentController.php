<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingPaymentController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date'
        ]);

        $balance = $booking->grand_total - $booking->paid_amount;

        if ($validated['amount'] > $balance) {
            throw ValidationException::withMessages([
                'amount' => 'Jumlah pembayaran melebihi sisa tagihan (Rp ' . number_format($balance) . ').'
            ]);
        }

        // Buat catatan pembayaran baru yang terhubung dengan booking ini
        $booking->payments()->create([
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
        ]);

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }
}
