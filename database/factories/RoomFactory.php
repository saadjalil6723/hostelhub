<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'room_number' => 'R'.fake()->unique()->numberBetween(100, 9999),
            'floor' => fake()->randomElement(['Ground', '1st', '2nd']),
            'room_type' => fake()->randomElement(['Single', 'Double Sharing', 'Triple Sharing']),
            'capacity' => 2,
            'current_occupancy' => 0,
            'price' => fake()->numberBetween(8000, 25000),
            'status' => 'available',
            'facilities' => 'AC, Wi-Fi, Attached Bath',
            'description' => fake()->sentence(),
        ];
    }
}
