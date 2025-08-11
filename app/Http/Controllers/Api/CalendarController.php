<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CalendarController extends Controller
{
    public function events()
    {
        $bookings = Booking::with(['guest', 'rooms'])
            ->whereIn('status', ['booked', 'checked_in'])
            ->get();

        $events = [];

        foreach ($bookings as $booking) {
            $roomNumbers = $booking->rooms->pluck('room_number')->implode(', #');
            $title = $booking->guest->name.' - #'.$roomNumbers;

            $color = match ($booking->status) {
                'booked' => '#3788d8',
                'checked_in' => '#f59e0b',
                default => '#6b7280',
            };

            $endDate = $booking->check_out_date ? Carbon::parse($booking->check_out_date) : Carbon::parse($booking->check_in_date)->addDay();

            $events[] = [
                'title' => $title,
                'start' => Carbon::parse($booking->check_in_date)->toIso8601String(),
                'end' => $endDate->toIso8601String(),
                'color' => $color,
                'url' => route('bookings.show', $booking->id),
                'extendedProps' => [
                    'booking_id' => $booking->id,
                    'status' => Str::title(str_replace('_', ' ', $booking->status)),
                ],
            ];
        }

        return response()->json($events);
    }
}
