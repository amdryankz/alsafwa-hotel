<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guest>
 */
class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $idType = $this->faker->randomElement(['KTP', 'Paspor']);
        $idNumber = ($idType === 'KTP')
            ? $this->faker->unique()->numerify('################')
            : $this->faker->unique()->bothify('??#######');

        return [
            'name' => $this->faker->name(),
            'id_type' => $idType,
            'id_number' => $idNumber,
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
