<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_first_visit_opens_one_time_admin_setup(): void
    {
        $this->get(route('admin.login'))
            ->assertRedirect(route('admin.setup'));

        $this->get(route('admin.setup'))
            ->assertOk()
            ->assertSee('Create your secure admin account.')
            ->assertSee('js/admin.js?v=', false);
    }

    public function test_valid_setup_creates_admin_and_signs_them_in(): void
    {
        $response = $this->post(route('admin.setup.store'), [
            'name' => 'Villy',
            'email' => 'villy@example.com',
            'password' => 'SecurePass123',
            'password_confirmation' => 'SecurePass123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'villy@example.com',
            'is_admin' => true,
        ]);
    }

    public function test_guest_is_redirected_and_non_admin_is_forbidden(): void
    {
        User::factory()->admin()->create();

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_sign_in_and_sign_out(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
        ]);

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
