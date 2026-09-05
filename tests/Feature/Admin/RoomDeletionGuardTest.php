<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Room;
use App\Models\RoomAllocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomDeletionGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Admin::factory()->create(), 'admin');
    }

    public function test_a_room_with_no_allocation_history_can_be_deleted(): void
    {
        $room = Room::factory()->create();

        $this->delete(route('admin.rooms.destroy', $room))
            ->assertRedirect(route('admin.rooms.index'));

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    public function test_a_room_with_an_active_allocation_cannot_be_deleted(): void
    {
        $room = Room::factory()->create();
        RoomAllocation::factory()->create(['room_id' => $room->id]);

        $this->delete(route('admin.rooms.destroy', $room))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('rooms', ['id' => $room->id]);
    }

    /**
     * Regression test: the original destroy() only checked
     * activeAllocations()->exists(), so a room whose ONLY allocation was
     * historical (already ended) could still be deleted — silently erasing
     * that resident's stay record via the cascade. It must now be blocked
     * regardless of allocation status.
     */
    public function test_a_room_with_only_ended_historical_allocations_cannot_be_deleted(): void
    {
        $room = Room::factory()->create();
        RoomAllocation::factory()->ended()->create(['room_id' => $room->id]);

        $this->delete(route('admin.rooms.destroy', $room))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('rooms', ['id' => $room->id]);
        $this->assertDatabaseCount('room_allocations', 1);
    }
}
