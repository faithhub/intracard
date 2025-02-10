<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
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
            'uuid' => $this->faker->uuid,
            'balance' => $this->faker->randomFloat(2, 100, 10000), // Random balance between 100 and 10000
            'details' => $this->faker->sentence(10),
        ];
    }
}
