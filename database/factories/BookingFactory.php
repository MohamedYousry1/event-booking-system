<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::pluck('id')->toArray();
        $events = Event::pluck('id')->toArray();
        return [
            'user_id' => $this->faker->randomElement($users),
            'event_id' => $this->faker->randomElement($events),
            'quantity' => $this->faker->numberBetween(1, 10),
            'total_price' => $this->faker->randomFloat(2, 100, 1000),
            'status' => 'pending',
            'notes' => $this->faker->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }
    public function confirmed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'confirmed',
        ]);
    }
    public function cancelled(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
    
}
