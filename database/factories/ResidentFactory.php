<?php

namespace Database\Factories;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidentFactory extends Factory
{
    protected $model = Resident::class;

    public function definition(): array
    {
        static $sequence = 0;
        $sequence++;

        return [
            'name' => fake()->name(),
            'guardian_name' => fake()->name(),
            'identification_number' => sprintf('%05d-%07d-%d', $sequence, $sequence + 1000, $sequence % 10),
            'id_type' => 'cnic',
            'phone' => sprintf('0%010d', 3000000000 + $sequence),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'emergency_contact' => sprintf('0%010d', 3100000000 + $sequence),
            'check_in_date' => now()->subDays(10),
            'check_out_date' => null,
            'status' => 'active',
            'notes' => null,
        ];
    }
}
