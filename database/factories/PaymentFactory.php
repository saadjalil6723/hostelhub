<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'resident_id' => Resident::factory(),
            'room_allocation_id' => null,
            'amount' => fake()->numberBetween(5000, 20000),
            'payment_date' => now(),
            'for_month' => now()->format('Y-m'),
            'method' => 'cash',
            'reference_number' => null,
            'notes' => null,
        ];
    }
}
