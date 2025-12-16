<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 days', '+10 days');
        $end = (clone $start)->modify('+4 hours');

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'start_date' => $start,
            'end_date' => $end,
            'location' => $this->faker->city(),
            'max_attendees' => $this->faker->numberBetween(50, 1000),
            'organizer_id' => User::factory()->organizer(),
            'status' => 'published',
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
