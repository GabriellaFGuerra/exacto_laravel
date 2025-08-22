<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appeal>
 */
class AppealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'infraction_id' => null, // Will be set by relationships or tests
            'subject' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'appeal' => fake()->filePath(),
            'status' => fake()->randomElement(['pending', 'approved', 'denied', 'under_review']),
        ];
    }
}
