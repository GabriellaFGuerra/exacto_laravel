<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
            'email' => fake()->unique()->safeEmail(),
            'login' => fake()->unique()->userName(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
            'user_type' => fake()->randomElement(['admin', 'customer']),
            'status' => fake()->boolean(80), // 80% chance of being active
            'notification' => fake()->numberBetween(0, 1),
            'address' => fake()->streetAddress(),
            'number' => fake()->buildingNumber(),
            'complement' => fake()->optional()->secondaryAddress(),
            'neighborhood' => fake()->citySuffix(),
            'municipality_id' => null, // Will be set by relationships or seeders
            'zip_code' => fake()->postcode(),
            'phone' => fake()->phoneNumber(),
            'cnpj' => fake()->optional()->numerify('##.###.###/####-##'),
            'cpf' => fake()->optional()->numerify('###.###.###-##'),
            'photo' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create an admin user.
     *
     * @return $this
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_type' => 'admin',
            'status' => true,
        ]);
    }

    /**
     * Create a customer user.
     *
     * @return $this
     */
    public function customer(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_type' => 'customer',
        ]);
    }

    /**
     * Create an inactive user.
     *
     * @return $this
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Create an active user.
     *
     * @return $this
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => true,
        ]);
    }
}