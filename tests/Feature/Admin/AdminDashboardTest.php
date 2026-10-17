<?php

namespace Tests\Feature\Admin;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_sees_practice_statistics_and_upcoming_work(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create(['name' => 'Emma Wilson']);
        Appointment::factory()->for($customer)->create([
            'starts_at' => now()->addDay()->setTime(10, 0),
            'treatment_slug' => 'sports-massage',
        ]);
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('PRACTICE OVERVIEW')
            ->assertSee('Emma Wilson')
            ->assertDontSee('Requests')
            ->assertSee('New this month');
    }

    public function test_admin_requests_page_is_not_available(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/requests')
            ->assertNotFound();
    }

    public function test_admin_can_view_a_month_calendar(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create(['name' => 'Olivia Brown']);
        Appointment::factory()->for($customer)->create([
            'starts_at' => '2026-11-18 14:30:00',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.calendar', ['month' => '2026-11']))
            ->assertOk()
            ->assertSee('November 2026')
            ->assertSee('Olivia Brown')
            ->assertSee('14:30')
            ->assertSee(route('admin.appointments.create', ['date' => '2026-11-18']), false)
            ->assertSee('New appointment on Wednesday, 18 November 2026');
    }
}
