<?php

use App\Models\Booking;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;

test('it logs an activity when a booking is updated', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $booking = Booking::factory()->create();

    $this->actingAs($admin)->post(route('bookings.adjustments.store', $booking), [
        'discount' => 50000,
        'tax_percentage' => 11,
    ]);

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'Transaksi',
        'description' => "Transaksi Booking #{$booking->id} telah di-updated",
        'subject_type' => Booking::class,
        'subject_id' => $booking->id,
        'causer_type' => User::class,
        'causer_id' => $admin->id,
    ]);
});

test('it logs an activity when an expense is created', function () {
    $accountant = User::factory()->create(['role' => 'accountant']);
    $category = ExpenseCategory::factory()->create();
    $expenseData = [
        'expense_category_id' => $category->id,
        'description' => 'Biaya Listrik',
        'amount' => 750000,
        'expense_date' => '2025-08-10',
    ];

    $this->actingAs($accountant)->post(route('expenses.store'), $expenseData);

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'Keuangan',
        'description' => "Data Pengeluaran 'Biaya Listrik' telah di-created",
        'subject_type' => Expense::class,
        'causer_id' => $accountant->id,
    ]);
});
