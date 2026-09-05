<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\RoomAllocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentDeletionGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Admin::factory()->create(), 'admin');
    }

    public function test_a_resident_with_no_history_can_be_deleted(): void
    {
        $resident = Resident::factory()->create();

        $this->delete(route('admin.residents.destroy', $resident))
            ->assertRedirect(route('admin.residents.index'));

        $this->assertDatabaseMissing('residents', ['id' => $resident->id]);
    }

    public function test_a_resident_with_allocation_history_cannot_be_deleted(): void
    {
        $resident = Resident::factory()->create();
        RoomAllocation::factory()->ended()->create(['resident_id' => $resident->id]);

        $this->delete(route('admin.residents.destroy', $resident))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('residents', ['id' => $resident->id]);
    }

    public function test_a_resident_with_payment_history_cannot_be_deleted(): void
    {
        $resident = Resident::factory()->create();
        Payment::factory()->create(['resident_id' => $resident->id]);

        $this->delete(route('admin.residents.destroy', $resident))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('residents', ['id' => $resident->id]);
    }
}
