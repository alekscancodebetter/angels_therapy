<?php

namespace Tests\Feature\Admin;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AppointmentManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_schedule_and_update_appointment(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_id' => $customer->id,
            'treatment_slug' => 'deep-tissue-massage',
            'starts_at' => '2026-11-20 11:00:00',
            'duration_minutes' => 60,
            'status' => 'scheduled',
            'notes' => 'First session.',
        ])->assertRedirect(route('admin.calendar', ['month' => '2026-11']));

        $appointment = Appointment::query()->firstOrFail();
        $this->assertSame('First session.', $appointment->notes);

        $this->actingAs($admin)->put(route('admin.appointments.update', $appointment), [
            'customer_id' => $customer->id,
            'treatment_slug' => 'deep-tissue-massage',
            'starts_at' => '2026-11-20 11:00:00',
            'duration_minutes' => 75,
            'status' => 'completed',
            'notes' => 'Treatment completed.',
        ])->assertRedirect(route('admin.calendar', ['month' => '2026-11']));

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'duration_minutes' => 75,
            'status' => 'completed',
        ]);
    }

    public function test_invalid_appointment_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_id' => 999,
            'treatment_slug' => 'unknown-treatment',
            'starts_at' => 'not-a-date',
            'duration_minutes' => 5,
            'status' => 'unknown',
        ])->assertSessionHasErrors(['customer_id', 'treatment_slug', 'starts_at', 'duration_minutes', 'status']);

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_admin_cannot_schedule_overlapping_appointments(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();
        Appointment::factory()->for($customer)->create([
            'starts_at' => '2026-11-20 11:00:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_id' => $customer->id,
            'treatment_slug' => 'deep-tissue-massage',
            'starts_at' => '2026-11-20 11:30:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
            'notes' => '',
        ])->assertSessionHasErrors(['starts_at']);

        $this->assertDatabaseCount('appointments', 1);
    }
}
