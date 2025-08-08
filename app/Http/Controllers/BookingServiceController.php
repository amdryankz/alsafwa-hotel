<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingServiceController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        // 1. Tambahkan layanan ke booking
        $booking->services()->create($validated);

        // 2. Update total tagihan di booking utama
        $totalServicePrice = $validated['price'] * $validated['quantity'];
        $booking->increment('total_amount', $totalServicePrice);

        return back()->with('success', 'Layanan berhasil ditambahkan.');
    }
}
