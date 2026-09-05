<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_correct_credentials_log_the_admin_in(): void
    {
        $admin = Admin::factory()->create(['password' => bcrypt('correct-password')]);

        $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_a_disabled_admin_account_cannot_log_in(): void
    {
        $admin = Admin::factory()->create([
            'password' => bcrypt('correct-password'),
            'is_active' => false,
        ]);

        $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'correct-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('admin');
    }

    /**
     * Regression test: /admin/login had no rate limiting at all, leaving it
     * open to unlimited brute-force attempts. It's now throttled to 5/min.
     */
    public function test_login_is_rate_limited_after_repeated_failed_attempts(): void
    {
        $admin = Admin::factory()->create(['password' => bcrypt('correct-password')]);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.attempt'), [
                'email' => $admin->email,
                'password' => 'wrong-password',
            ]);
        }

        // The 6th attempt within the same minute should be throttled (429),
        // even with the correct password.
        $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'correct-password',
        ])->assertStatus(429);

        $this->assertGuest('admin');
    }
}
