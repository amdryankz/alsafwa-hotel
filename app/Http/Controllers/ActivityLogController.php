<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = Activity::with('causer') // 'causer' adalah relasi ke model User
            ->latest() // Urutkan dari yang terbaru
            ->paginate(20);

        return view('activity_log.index', compact('activities'));
    }
}
