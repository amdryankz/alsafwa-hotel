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
            'payment_method' => 'required|string'
        ]);

        $balance = $booking->total_amount - $booking->paid_amount;

        // Validasi agar pembayaran tidak melebihi sisa tagihan
        if ($validated['amount'] > $balance) {
            throw ValidationException::withMessages([
                'amount' => 'Jumlah pembayaran melebihi sisa tagihan (Rp ' . number_format($balance) . ').'
            ]);
        }

        // 1. Tambahkan jumlah pembayaran
        $booking->increment('paid_amount', $validated['amount']);

        // 2. Update metode pembayaran (jika perlu)
        if (is_null($booking->payment_method)) {
            $booking->update(['payment_method' => $validated['payment_method']]);
        }

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }
}
