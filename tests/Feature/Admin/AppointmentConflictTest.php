<?php

namespace Tests\Feature\Admin;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AppointmentConflictTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_receives_conflict_details_for_overlapping_time(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create(['name' => 'Alice Example']);
        $appointment = Appointment::factory()->for($customer)->create([
            'starts_at' => '2026-11-20 13:00:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.appointments.conflict', [
                'starts_at' => '2026-11-20 12:30',
                'duration_minutes' => 60,
            ]))
            ->assertOk()
            ->assertHeader('Cache-Control', 'must-revalidate, no-cache, no-store, private')
            ->assertExactJson([
                'available' => false,
                'message' => 'Conflict: Alice Example has an appointment from 13:00 to 14:00.',
                'conflict' => [
                    'appointment_id' => $appointment->id,
                    'customer_name' => 'Alice Example',
                    'starts_at' => '2026-11-20 13:00',
                    'ends_at' => '2026-11-20 14:00',
                ],
            ]);
    }

    public function test_admin_receives_available_result_when_time_does_not_overlap(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();
        Appointment::factory()->for($customer)->create([
            'starts_at' => '2026-11-20 13:00:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.appointments.conflict', [
                'starts_at' => '2026-11-20 14:00',
                'duration_minutes' => 90,
            ]))
            ->assertOk()
            ->assertExactJson([
                'available' => true,
                'message' => 'This time is available.',
                'conflict' => null,
            ]);
    }

    public function test_editing_appointment_ignores_that_same_appointment(): void
    {
        $admin = User::factory()->admin()->create();
        $appointment = Appointment::factory()->create([
            'starts_at' => '2026-11-20 13:00:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.appointments.conflict', [
                'starts_at' => '2026-11-20 13:00',
                'duration_minutes' => 60,
                'appointment_id' => $appointment->id,
            ]))
            ->assertOk()
            ->assertJsonPath('available', true)
            ->assertJsonPath('conflict', null);
    }

    public function test_conflict_check_requires_admin_access(): void
    {
        $this->getJson(route('admin.appointments.conflict', [
            'starts_at' => '2026-11-20 13:00',
            'duration_minutes' => 60,
        ]))->assertUnauthorized();

        $this->actingAs(User::factory()->create())
            ->getJson(route('admin.appointments.conflict', [
                'starts_at' => '2026-11-20 13:00',
                'duration_minutes' => 60,
            ]))
            ->assertForbidden();
    }
}
