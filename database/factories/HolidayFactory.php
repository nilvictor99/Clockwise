<?php

namespace Database\Factories;

use App\Enums\HolidayTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Holiday>
 */
class HolidayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'calendar' => fake()->date(),
            'staff_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'start_day' => fake()->dateTimeBetween('-1 month', 'now'),
            'end_day' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'type' => fake()->randomElement(HolidayTypeEnum::values()),
            'description' => fake()->sentence(),
        ];
    }
}
