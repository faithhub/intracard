<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WalletTransaction>
 */
class WalletTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 5), // Assuming you have users with IDs 1-10
            'wallet_id' => $this->faker->numberBetween(1, 5), // Assuming wallet IDs 1-10
            'uuid' => $this->faker->uuid,
            'amount' => $this->faker->randomFloat(2, 10, 1000), // Random amount between 10 and 1000
            'charge' => $this->faker->randomFloat(2, 1, 50), // Random charge between 1 and 50
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'type' => $this->faker->randomElement(['inbound', 'outbound']),
            'details' => $this->faker->sentence(15),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
