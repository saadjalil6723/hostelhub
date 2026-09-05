<?php

namespace Database\Factories;

use App\Models\Resident;
use App\Models\Room;
use App\Models\RoomAllocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomAllocationFactory extends Factory
{
    protected $model = RoomAllocation::class;

    public function definition(): array
    {
        return [
            'resident_id' => Resident::factory(),
            'room_id' => Room::factory(),
            'bed_number' => 'A',
            'allocation_date' => now()->subDays(5),
            'checkout_date' => null,
            'status' => 'active',
        ];
    }

    public function ended(): static
    {
        return $this->state(fn () => [
            'status' => 'ended',
            'checkout_date' => now(),
        ]);
    }
}
