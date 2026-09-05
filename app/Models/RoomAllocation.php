<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'room_id',
        'bed_number',
        'allocation_date',
        'checkout_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'allocation_date' => 'date',
            'checkout_date' => 'date',
        ];
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
