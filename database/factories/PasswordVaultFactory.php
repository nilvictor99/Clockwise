<?php

namespace Database\Factories;

use App\Enums\PasswordTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PasswordVault>
 */
class PasswordVaultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'name' => fake()->sentence(3),
            'username' => fake()->userName(),
            'password' => fake()->password(),
            'url' => fake()->url(),
            'notes' => fake()->paragraph(),
            'type' => fake()->randomElement(PasswordTypeEnum::values()),
            'active' => fake()->boolean(),
        ];
    }
}
