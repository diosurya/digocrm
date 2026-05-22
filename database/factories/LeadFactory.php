<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
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
            'email' => fake()->safeEmail(),
            'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'lost']),
            'source' => fake()->randomElement(['Website', 'Referral', 'Social Media', 'Advertisement']),
        ];
    }
}
