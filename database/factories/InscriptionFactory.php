<?php

namespace Database\Factories;

use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inscription>
 */
class InscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'formation_id' => Formation::factory(),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'telephone' => fake()->numerify('+226 ## ## ## ##'),
            'email' => fake()->unique()->safeEmail(),
            'direction_service' => fake()->randomElement([
                'Direction des Sports',
                'Direction de la Jeunesse',
                'Direction de l\'Emploi',
                'Direction des Ressources Humaines',
                'Direction des Affaires Financières',
                'Service Communication',
            ]),
        ];
    }
}
