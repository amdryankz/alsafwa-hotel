<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAdjustmentController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'discount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $booking->update([
            'discount' => $validated['discount'] ?? 0,
            'tax_percentage' => $validated['tax_percentage'] ?? 0,
        ]);

        return back()->with('success', 'Diskon dan PPN berhasil diperbarui.');
    }
}
