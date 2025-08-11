<?php

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;

beforeEach(function () {
    $this->category = ExpenseCategory::factory()->create();
    $this->accountant = User::factory()->create(['role' => 'accountant']);
    $this->staff = User::factory()->create(['role' => 'front_office']);
});

test('accountant can view the expense list page', function () {
    $expense = Expense::factory()->create(['expense_category_id' => $this->category->id]);

    $this->actingAs($this->accountant)
        ->get(route('expenses.index'))
        ->assertOk()
        ->assertSeeText('Data Pengeluaran')
        ->assertSeeText($expense->description);
});

test('front office staff cannot access the expense page', function () {
    $this->actingAs($this->staff)
        ->get(route('expenses.index'))
        ->assertForbidden();
});

test('accountant can create a new expense', function () {
    $expenseData = [
        'expense_category_id' => $this->category->id,
        'description' => 'Pembelian ATK Bulan Agustus',
        'amount' => 250000,
        'expense_date' => '2025-08-11',
    ];

    $this->actingAs($this->accountant)
        ->post(route('expenses.store'), $expenseData);

    $this->assertDatabaseHas('expenses', [
        'description' => 'Pembelian ATK Bulan Agustus',
        'amount' => 250000,
    ]);
});

test('accountant can update an expense', function () {
    $expense = Expense::factory()->create(['expense_category_id' => $this->category->id]);

    $updatedData = [
        'expense_category_id' => $this->category->id,
        'description' => 'Revisi Pembelian ATK',
        'amount' => 300000,
        'expense_date' => $expense->expense_date,
    ];

    $this->actingAs($this->accountant)
        ->put(route('expenses.update', $expense), $updatedData);

    $this->assertDatabaseHas('expenses', [
        'id' => $expense->id,
        'description' => 'Revisi Pembelian ATK',
        'amount' => 300000,
    ]);
});

test('accountant can delete an expense', function () {
    $expense = Expense::factory()->create();

    $this->actingAs($this->accountant)
        ->delete(route('expenses.destroy', $expense));

    $this->assertDatabaseMissing('expenses', [
        'id' => $expense->id,
    ]);
});
