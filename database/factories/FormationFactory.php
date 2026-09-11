<?php

namespace Database\Factories;

use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Formation>
 */
class FormationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'lieu' => fake()->city(),
            'debut_a' => fake()->dateTimeBetween('+1 week', '+2 months'),
            'places' => fake()->numberBetween(10, 40),
        ];
    }
}
