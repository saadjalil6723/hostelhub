<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceStatusToggleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Admin::factory()->create(), 'admin');
    }

    /**
     * Regression test: the create/edit form's checkbox used to have no
     * hidden fallback input, so an unchecked box sent NO `status` key at
     * all, and the controller's `$request->boolean('status', true)` masked
     * that by defaulting to true — meaning a service could never actually
     * be disabled through the UI.
     */
    public function test_unchecking_status_disables_the_service(): void
    {
        $service = Service::factory()->create(['status' => true]);

        $this->put(route('admin.services.update', $service), [
            'title' => $service->title,
            'description' => $service->description,
            'status' => '0', // what the form's hidden fallback input sends when unchecked
        ])->assertRedirect(route('admin.services.index'));

        $this->assertFalse($service->fresh()->status);
    }

    public function test_checking_status_enables_the_service(): void
    {
        $service = Service::factory()->create(['status' => false]);

        $this->put(route('admin.services.update', $service), [
            'title' => $service->title,
            'description' => $service->description,
            'status' => '1',
        ])->assertRedirect(route('admin.services.index'));

        $this->assertTrue($service->fresh()->status);
    }

    /**
     * Defends specifically against reintroducing `boolean('status', true)`:
     * if the key is missing entirely (e.g. a direct API call bypassing the
     * form's hidden input), the service must NOT silently stay/become
     * enabled by default.
     */
    public function test_missing_status_key_defaults_to_disabled_not_enabled(): void
    {
        $service = Service::factory()->create(['status' => true]);

        $this->put(route('admin.services.update', $service), [
            'title' => $service->title,
            'description' => $service->description,
            // no 'status' key at all
        ])->assertRedirect(route('admin.services.index'));

        $this->assertFalse($service->fresh()->status);
    }

    public function test_disabled_services_are_hidden_from_the_public_site(): void
    {
        $visible = Service::factory()->create(['status' => true, 'title' => 'Visible Service']);
        $hidden = Service::factory()->create(['status' => false, 'title' => 'Hidden Service']);

        $response = $this->get(route('rooms.index'));

        $response->assertSee('Visible Service');
        $response->assertDontSee('Hidden Service');
    }
}
