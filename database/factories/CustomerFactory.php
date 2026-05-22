<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'whatsapp' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'order' => fake()->sentence(3),
            'location' => fake()->city(),
            'purchase_date' => fake()->date(),
            'important_chat' => fake()->paragraph(),
        ];
    }
}
