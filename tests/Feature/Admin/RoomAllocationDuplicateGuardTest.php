<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Resident;
use App\Models\Room;
use App\Models\RoomAllocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomAllocationDuplicateGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Admin::factory()->create(), 'admin');
    }

    public function test_allocating_a_resident_who_already_has_an_active_allocation_is_blocked_by_default(): void
    {
        $resident = Resident::factory()->create();
        $oldRoom = Room::factory()->create(['capacity' => 2]);
        RoomAllocation::factory()->create(['resident_id' => $resident->id, 'room_id' => $oldRoom->id, 'status' => 'active']);
        $newRoom = Room::factory()->create(['capacity' => 2]);

        $this->post(route('admin.allocations.store'), [
            'resident_id' => $resident->id,
            'room_id' => $newRoom->id,
            'allocation_date' => now()->toDateString(),
        ])->assertSessionHasErrors('resident_id');

        $this->assertDatabaseCount('room_allocations', 1);
    }

    public function test_confirm_reallocate_ends_the_old_allocation_and_creates_a_new_one(): void
    {
        $resident = Resident::factory()->create();
        $oldRoom = Room::factory()->create(['capacity' => 2]);
        $oldAllocation = RoomAllocation::factory()->create(['resident_id' => $resident->id, 'room_id' => $oldRoom->id, 'status' => 'active']);
        $oldRoom->syncOccupancy();
        $newRoom = Room::factory()->create(['capacity' => 2]);

        $this->post(route('admin.allocations.store'), [
            'resident_id' => $resident->id,
            'room_id' => $newRoom->id,
            'allocation_date' => now()->toDateString(),
            'confirm_reallocate' => '1',
        ])->assertRedirect(route('admin.allocations.index'));

        $this->assertSame('ended', $oldAllocation->fresh()->status);
        $this->assertDatabaseHas('room_allocations', [
            'resident_id' => $resident->id,
            'room_id' => $newRoom->id,
            'status' => 'active',
        ]);
    }

    public function test_allocating_to_a_full_room_is_rejected(): void
    {
        $resident = Resident::factory()->create();
        $room = Room::factory()->create(['capacity' => 1]);
        RoomAllocation::factory()->create(['room_id' => $room->id, 'status' => 'active']);
        $room->syncOccupancy();

        $this->assertSame('full', $room->fresh()->status);

        $this->post(route('admin.allocations.store'), [
            'resident_id' => $resident->id,
            'room_id' => $room->id,
            'allocation_date' => now()->toDateString(),
        ])->assertRedirect()->assertSessionHas('error');

        $this->assertDatabaseCount('room_allocations', 1);
    }
}
