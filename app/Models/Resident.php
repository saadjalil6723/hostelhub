<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guardian_name',
        'identification_number',
        'id_type',
        'phone',
        'email',
        'address',
        'emergency_contact',
        'check_in_date',
        'check_out_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
        ];
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(RoomAllocation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function currentAllocation()
    {
        return $this->allocations()->where('status', 'active')->latest('allocation_date')->latest('id')->first();
    }
}
