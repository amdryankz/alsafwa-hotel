<?php

namespace Database\Factories;

use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalAmount = $this->faker->randomFloat(2, 500000, 10000000);
        $paidAmount = $this->faker->randomFloat(2, 0, $totalAmount);

        return [
            'guest_id' => Guest::factory(),
            'check_in_date' => Carbon::parse('2025-08-15 14:00:00'),
            'check_out_date' => Carbon::parse('2025-08-17 12:00:00'),
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'payment_method' => $this->faker->randomElement(['cash', 'transfer', 'qris', 'card']),
            'status' => $this->faker->randomElement(['booked', 'checked_in', 'checked_out', 'cancelled']),
            'notes' => $this->faker->boolean(30) ? $this->faker->paragraph() : null,
            'discount' => $this->faker->randomFloat(2, 0, 500000),
            'tax_percentage' => 11.00,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
