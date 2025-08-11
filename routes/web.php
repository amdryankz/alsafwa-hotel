<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\BookingAdjustmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\BookingServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:admin,front_office')->group(function () {
        Route::resource('guests', GuestController::class);
        Route::post('/bookings/{booking}/services', [BookingServiceController::class, 'store'])->name('bookings.services.store');
        Route::post('/bookings/{booking}/payments', [BookingPaymentController::class, 'store'])->name('bookings.payments.store');
        Route::post('/bookings/{booking}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
        Route::resource('bookings', BookingController::class);
        Route::get('/bookings/{booking}/print', [BookingController::class, 'print'])->name('bookings.print');
        Route::post('/bookings/{booking}/adjustments', [BookingAdjustmentController::class, 'store'])->name('bookings.adjustments.store');
        Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/bookings/{booking}/change-room', [BookingController::class, 'changeRoom'])->name('bookings.changeRoom');
        Route::post('/bookings/{booking}/confirm-checkin', [BookingController::class, 'confirmCheckIn'])->name('bookings.confirmCheckIn');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('room-types', RoomTypeController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('users', UserController::class);
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    });

    Route::middleware(['role:owner,accountant,admin'])->group(function () {
        Route::get('/reports/financial', [ReportController::class, 'index'])->name('reports.financial');
        Route::resource('expense-categories', ExpenseCategoryController::class);
        Route::resource('expenses', ExpenseController::class);
        Route::get('/reports/financial/export', [ReportController::class, 'exportExcel'])->name('reports.financial.export');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/calendar-events', [CalendarController::class, 'events']);
});

require __DIR__.'/auth.php';
