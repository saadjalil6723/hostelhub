<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'floor',
        'room_type',
        'capacity',
        'current_occupancy',
        'price',
        'status',
        'facilities',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'capacity' => 'integer',
            'current_occupancy' => 'integer',
        ];
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(RoomAllocation::class);
    }

    public function activeAllocations(): HasMany
    {
        return $this->allocations()->where('status', 'active');
    }

    public function isFull(): bool
    {
        return $this->current_occupancy >= $this->capacity;
    }

    /**
     * Recalculate occupancy + status from active allocations.
     * Called after every allocation create/update/delete.
     */
    public function syncOccupancy(): void
    {
        $occupied = $this->activeAllocations()->count();

        $status = match (true) {
            $this->status === 'maintenance' => 'maintenance',
            $occupied === 0 => 'available',
            $occupied >= $this->capacity => 'full',
            default => 'partially_occupied',
        };

        $this->update([
            'current_occupancy' => $occupied,
            'status' => $status,
        ]);
    }
}
