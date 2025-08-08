<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guests = Guest::latest()->paginate(10);
        return view('guests.index', compact('guests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_type' => 'required|in:KTP,Paspor',
            'id_number' => 'required|string|unique:guests,id_number',
            'phone_number' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        Guest::create($validated);

        return redirect()->route('guests.index')->with('success', 'Data tamu berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest)
    {
        $guest->load(['bookings' => function ($query) {
            $query->with('rooms')->latest(); // Urutkan booking dari yang terbaru
        }]);

        return view('guests.show', compact('guest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest)
    {
        return view('guests.edit', compact('guest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_type' => 'required|in:KTP,Paspor',
            'id_number' => 'required|string|unique:guests,id_number,' . $guest->id,
            'phone_number' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        $guest->update($validated);

        return redirect()->route('guests.index')->with('success', 'Data tamu berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        $guest->delete();
        return redirect()->route('guests.index')->with('success', 'Data tamu berhasil dihapus!');
    }
}
