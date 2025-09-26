<?php

namespace Database\Factories;

use App\Enums\PermissionsPasswordEnum;
use App\Models\PasswordVault;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PasswordShare>
 */
class PasswordShareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'password_id' => PasswordVault::inRandomOrder()->first()->id ?? PasswordVault::factory()->create()->id,
            'shared_by' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'shared_with' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'permissions' => fake()->randomElement(PermissionsPasswordEnum::values()),
        ];
    }
}
