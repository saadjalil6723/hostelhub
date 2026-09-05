<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Resident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Admin::factory()->create(), 'admin');
    }

    /**
     * Regression test for the orWhere/AND-precedence bug: combining `search`
     * with `status` used to silently drop the status filter for any resident
     * whose *phone or ID number* (not just name) matched the search term,
     * because `->where('name', ...)->orWhere(...)->orWhere(...)` breaks out
     * of the surrounding `->when('status', ...)` AND clause without a
     * grouping closure.
     */
    public function test_search_and_status_filters_combine_with_and_not_or(): void
    {
        $matchingActive = Resident::factory()->create([
            'name' => 'Ayesha Khan',
            'phone' => '03001234567',
            'status' => 'active',
        ]);

        $matchingInactive = Resident::factory()->create([
            'name' => 'Ayesha Bukhari',
            'phone' => '03007654321',
            'status' => 'inactive',
        ]);

        $nonMatchingActive = Resident::factory()->create([
            'name' => 'Bilal Ahmed',
            'phone' => '03009999999',
            'status' => 'active',
        ]);

        $response = $this->get(route('admin.residents.index', [
            'search' => 'Ayesha',
            'status' => 'active',
        ]));

        $response->assertOk();

        $ids = $response->viewData('residents')->pluck('id');

        $this->assertTrue($ids->contains($matchingActive->id), 'Expected the matching ACTIVE resident to be included.');
        $this->assertFalse($ids->contains($matchingInactive->id), 'A resident matching the search but NOT the status filter leaked through — the AND/OR grouping bug is back.');
        $this->assertFalse($ids->contains($nonMatchingActive->id), 'A resident matching the status but not the search leaked through.');
    }

    public function test_search_matches_phone_and_identification_number_too(): void
    {
        $byPhone = Resident::factory()->create(['phone' => '03331112222']);
        $byId = Resident::factory()->create(['identification_number' => '99999-9999999-9']);
        $noMatch = Resident::factory()->create(['phone' => '03000000000', 'identification_number' => '11111-1111111-1']);

        $response = $this->get(route('admin.residents.index', ['search' => '3331112222']));
        $ids = $response->viewData('residents')->pluck('id');
        $this->assertTrue($ids->contains($byPhone->id));
        $this->assertFalse($ids->contains($noMatch->id));

        $response = $this->get(route('admin.residents.index', ['search' => '99999-9999999-9']));
        $ids = $response->viewData('residents')->pluck('id');
        $this->assertTrue($ids->contains($byId->id));
    }
}
