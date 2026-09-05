<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentIdentificationValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(Admin::factory()->create(), 'admin');
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test Resident',
            'id_type' => 'cnic',
            'phone' => '03001234567',
            'status' => 'active',
        ], $overrides);
    }

    public function test_cnic_id_type_requires_the_dashed_cnic_format(): void
    {
        $this->post(route('admin.residents.store'), $this->validPayload([
            'id_type' => 'cnic',
            'identification_number' => 'AB1234567', // not a CNIC shape
        ]))->assertSessionHasErrors('identification_number');
    }

    public function test_cnic_id_type_accepts_a_valid_cnic(): void
    {
        $this->post(route('admin.residents.store'), $this->validPayload([
            'id_type' => 'cnic',
            'identification_number' => '35202-1234567-1',
        ]))->assertSessionDoesntHaveErrors('identification_number');

        $this->assertDatabaseHas('residents', ['identification_number' => '35202-1234567-1']);
    }

    /**
     * Regression test: the identification_number field originally applied
     * the CNIC regex unconditionally, which meant a passport-holding
     * (e.g. foreign) resident could never be added because their ID
     * number legitimately isn't a 13-digit Pakistani CNIC.
     */
    public function test_passport_id_type_is_not_forced_into_cnic_format(): void
    {
        $this->post(route('admin.residents.store'), $this->validPayload([
            'id_type' => 'passport',
            'identification_number' => 'AB1234567',
        ]))->assertSessionDoesntHaveErrors('identification_number');

        $this->assertDatabaseHas('residents', ['identification_number' => 'AB1234567', 'id_type' => 'passport']);
    }

    public function test_a_13_digit_cnic_typed_without_dashes_is_normalized_before_saving(): void
    {
        $this->post(route('admin.residents.store'), $this->validPayload([
            'id_type' => 'cnic',
            'identification_number' => '3520212345671',
        ]))->assertSessionDoesntHaveErrors('identification_number');

        $this->assertDatabaseHas('residents', ['identification_number' => '35202-1234567-1']);
    }
}
