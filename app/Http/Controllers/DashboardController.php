<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;

class DashboardController extends Controller
{
    public function index()
    {
        $revenueToday = Payment::whereDate('payment_date', today())->sum('amount');

        $checkInsToday = Booking::whereDate('check_in_date', today())->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $totalRooms = Room::count();
        $occupancyRate = ($totalRooms > 0) ? ($occupiedRooms / $totalRooms) * 100 : 0;

        return view('dashboard', [
            'revenueToday' => $revenueToday,
            'checkInsToday' => $checkInsToday,
            'occupancyRate' => $occupancyRate,
            'occupiedRooms' => $occupiedRooms,
            'totalRooms' => $totalRooms
        ]);
    }
}
