<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timesheet>
 */
class TimesheetFactory extends Factory
{
    public function definition(): array
    {
        $dayIn = fake()->dateTimeBetween('-1 month', 'now')->setTime(rand(8, 12), rand(0, 59));
        $dayOut = clone $dayIn;
        $dayOut->setTime(rand(16, 20), rand(0, 59));
        $totalMinutes = $dayIn->diff($dayOut)->h * 60 + $dayIn->diff($dayOut)->i;
        $hours = sprintf('%02d:%02d:00', intdiv($totalMinutes, 60), $totalMinutes % 60);

        return [
            'calendar' => fake()->date(),
            'staff_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'type' => fake()->randomElement(\App\Enums\TypeTimesheetEnum::values()),
            'day_in' => $dayIn,
            'day_out' => $dayOut,
            'hours' => $hours,
            'location' => json_encode([
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
            ]),
        ];
    }
}
