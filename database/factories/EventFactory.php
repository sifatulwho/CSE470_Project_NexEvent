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
        ];
    }
}
