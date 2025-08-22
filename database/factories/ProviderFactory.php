<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'address' => fake()->streetAddress(),
            'number' => fake()->buildingNumber(),
            'phone' => fake()->phoneNumber(),
            'phone_2' => fake()->optional()->phoneNumber(),
            'cnpj' => fake()->unique()->numerify('##.###.###/####-##'),
            'municipality_id' => null, // Will be set by relationships or tests
            'complement' => fake()->optional()->secondaryAddress(),
            'neighborhood' => fake()->citySuffix(),
            'zip_code' => fake()->postcode(),
        ];
    }
}
