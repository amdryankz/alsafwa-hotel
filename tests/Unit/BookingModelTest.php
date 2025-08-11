<?php

use App\Models\Booking;
use App\Models\Payment;

test('grand total is calculated correctly', function () {
    $booking = new Booking([
        'total_amount' => 1000000,
        'discount' => 100000,
        'tax_percentage' => 11,
    ]);

    $expectedGrandTotal = 999000;

    $calculatedGrandTotal = $booking->grand_total;

    $this->assertEquals($expectedGrandTotal, $calculatedGrandTotal);
});

test('paid amount accessor sums payments correctly', function () {
    $booking = new Booking;

    $payments = collect([
        new Payment(['amount' => 200000]),
        new Payment(['amount' => 300000]),
    ]);

    $booking->setRelation('payments', $payments);

    $calculatedPaidAmount = $booking->paid_amount;

    $this->assertEquals(500000, $calculatedPaidAmount);
});
