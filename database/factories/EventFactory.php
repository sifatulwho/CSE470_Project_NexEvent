<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organizer_id' => User::factory()->organizer(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'start_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'end_date' => fake()->dateTimeBetween('+3 months', '+4 months'),
            'location' => fake()->address(),
            'category' => fake()->randomElement(['Conference', 'Workshop', 'Seminar', 'Webinar', 'Social']),
            'capacity' => fake()->numberBetween(50, 500),
            'status' => fake()->randomElement(['scheduled', 'ongoing', 'completed']),
        ];
    }
}
