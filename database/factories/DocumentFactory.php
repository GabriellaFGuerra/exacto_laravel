<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => null, // Will be set by relationships or tests
            'budget_id' => null, // Will be set by relationships or tests
            'document_type_id' => null, // Will be set by relationships or tests
            'attachment' => fake()->filePath(),
            'title' => fake()->sentence(4),
            'issue_date' => fake()->optional()->date(),
            'periodicity' => fake()->optional()->numberBetween(1, 12),
            'expiration_date' => fake()->optional()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'observation' => fake()->optional()->paragraph(),
        ];
    }
}
