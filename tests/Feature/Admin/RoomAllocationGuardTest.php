<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Room;
use App\Models\RoomAllocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomAllocationGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Admin::factory()->create(), 'admin');
    }

    public function test_ending_an_active_allocation_sets_status_and_checkout_date(): void
    {
        $room = Room::factory()->create(['capacity' => 2]);
        $allocation = RoomAllocation::factory()->create(['room_id' => $room->id, 'status' => 'active']);
        $room->syncOccupancy();

        $this->patch(route('admin.allocations.end', $allocation))
            ->assertRedirect(route('admin.allocations.index'));

        $allocation->refresh();
        $this->assertSame('ended', $allocation->status);
        $this->assertNotNull($allocation->checkout_date);
    }

    /**
     * Regression test: ending an allocation a second time used to just
     * overwrite checkout_date with a fresh now() every time, with no guard
     * against the allocation already being ended. The first recorded
     * checkout date must be preserved.
     */
    public function test_ending_an_already_ended_allocation_is_rejected_and_preserves_the_original_checkout_date(): void
    {
        $allocation = RoomAllocation::factory()->ended()->create();
        $originalCheckoutDate = $allocation->checkout_date;

        $this->travel(3)->days();

        $this->patch(route('admin.allocations.end', $allocation))
            ->assertRedirect()
            ->assertSessionHas('error');

        $allocation->refresh();
        $this->assertTrue($allocation->checkout_date->equalTo($originalCheckoutDate));
    }

    /**
     * Regression test: deleting an allocation record used to work
     * regardless of status, so a still-active allocation could be hard
     * deleted, silently freeing the resident's bed without ever recording
     * that they checked out.
     */
    public function test_deleting_a_still_active_allocation_is_rejected(): void
    {
        $room = Room::factory()->create(['capacity' => 2]);
        $allocation = RoomAllocation::factory()->create(['room_id' => $room->id, 'status' => 'active']);

        $this->delete(route('admin.allocations.destroy', $allocation))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('room_allocations', ['id' => $allocation->id, 'status' => 'active']);
    }

    public function test_deleting_an_ended_allocation_succeeds_and_resyncs_room_occupancy(): void
    {
        $room = Room::factory()->create(['capacity' => 2, 'current_occupancy' => 1, 'status' => 'partially_occupied']);
        $allocation = RoomAllocation::factory()->ended()->create(['room_id' => $room->id]);

        $this->delete(route('admin.allocations.destroy', $allocation))
            ->assertRedirect(route('admin.allocations.index'));

        $this->assertDatabaseMissing('room_allocations', ['id' => $allocation->id]);
    }

    public function test_room_occupancy_and_status_stay_in_sync_through_the_allocation_lifecycle(): void
    {
        $room = Room::factory()->create(['capacity' => 1, 'status' => 'available', 'current_occupancy' => 0]);
        $allocation = RoomAllocation::factory()->create(['room_id' => $room->id, 'status' => 'active']);
        $room->syncOccupancy();

        $room->refresh();
        $this->assertSame(1, $room->current_occupancy);
        $this->assertSame('full', $room->status);

        $this->patch(route('admin.allocations.end', $allocation));

        $room->refresh();
        $this->assertSame(0, $room->current_occupancy);
        $this->assertSame('available', $room->status);
    }
}
