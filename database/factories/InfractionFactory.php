<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infraction>
 */
class InfractionFactory extends Factory
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
            'type' => fake()->randomElement(['noise', 'construction', 'environmental', 'traffic', 'other']),
            'year' => fake()->year(),
            'city' => fake()->city(),
            'date' => fake()->optional()->date(),
            'owner' => fake()->name(),
            'apt' => fake()->optional()->buildingNumber(),
            'block' => fake()->optional()->bothify('Block ##'),
            'address' => fake()->address(),
            'email' => fake()->optional()->safeEmail(),
            'irregularity_description' => fake()->optional()->paragraph(),
            'subject' => fake()->sentence(),
            'article_description' => fake()->optional()->paragraphs(2, true),
            'notification_description' => fake()->optional()->paragraphs(2, true),
            'receipt' => fake()->optional()->filePath(),
        ];
    }
}