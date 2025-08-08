<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Metrik untuk Stat Cards
        $revenueToday = Booking::where('status', 'checked_out')
            ->whereDate('check_out_date', today())
            ->sum('total_amount');

        $checkInsToday = Booking::whereDate('check_in_date', today())->count();

        $occupiedRooms = Room::where('status', 'occupied')->count();
        $totalRooms = Room::count();
        $occupancyRate = ($totalRooms > 0) ? ($occupiedRooms / $totalRooms) * 100 : 0;

        // Data untuk Grafik Pendapatan 7 Hari Terakhir
        $revenueData = [];
        $dateLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = Booking::where('status', 'checked_out')
                ->whereDate('check_out_date', $date)
                ->sum('total_amount');

            $revenueData[] = $revenue;
            $dateLabels[] = $date->format('d M');
        }

        return view('dashboard', [
            'revenueToday' => $revenueToday,
            'checkInsToday' => $checkInsToday,
            'occupancyRate' => $occupancyRate,
            'occupiedRooms' => $occupiedRooms,
            'totalRooms' => $totalRooms,
            'revenueChart' => [
                'labels' => $dateLabels,
                'data' => $revenueData,
            ]
        ]);
    }
}
