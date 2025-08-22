<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
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
            'service_type_id' => null, // Will be set by relationships or tests
            'custom_service_type' => fake()->optional()->words(3, true),
            'spreadsheet' => fake()->optional()->filePath(),
            'progress' => fake()->optional()->paragraph(),
            'observation' => fake()->optional()->sentence(),
            'approval_date' => fake()->optional()->date(),
            'responsible_user_id' => null, // Will be set by relationships or tests
            'responsible_manager_id' => null, // Will be set by relationships or tests
            'deadline' => fake()->optional()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'status' => fake()->randomElement(['open', 'approved', 'rejected', 'pending']),
        ];
    }
}