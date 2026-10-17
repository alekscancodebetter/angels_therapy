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

    public function test_appointment_form_has_clear_customer_choices_and_supported_booking_options(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create([
            'name' => 'Alice Example',
            'email' => 'alice@example.com',
            'phone' => '07123 456789',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.appointments.create'))
            ->assertOk()
            ->assertSee('type="search"', false)
            ->assertSee('list="admin-customer-options"', false)
            ->assertSee('Alice Example · alice@example.com · 07123 456789')
            ->assertSee('Existing customer')
            ->assertSee('New customer')
            ->assertSee('Sports Massage')
            ->assertSee('Deep Tissue Massage')
            ->assertSee('admin-dropdown', false)
            ->assertSee('admin-dropdown__icon', false)
            ->assertSee('Treatment options')
            ->assertSee('Appointment time options')
            ->assertSee('Duration options')
            ->assertSee('Status options')
            ->assertSee('name="appointment_time"', false)
            ->assertSee('value="08:00"', false)
            ->assertSee('value="12:30"', false)
            ->assertSee('value="19:00"', false)
            ->assertSee('60 minutes')
            ->assertSee('90 minutes')
            ->assertSee('Scheduled')
            ->assertSee('Completed')
            ->assertSee('data-appointment-conflict', false)
            ->assertSee(route('admin.appointments.conflict'), false)
            ->assertDontSee('value="12:10"', false)
            ->assertDontSee('value="19:30"', false)
            ->assertDontSee('45 minutes');

        $this->assertSame(4, substr_count($response->getContent(), 'data-admin-dropdown '));
    }

    public function test_admin_can_schedule_and_update_appointment(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_id' => $customer->id,
            'treatment_slug' => 'deep-tissue-massage',
            'appointment_date' => '2026-11-20',
            'appointment_time' => '11:00',
            'duration_minutes' => 60,
            'status' => 'scheduled',
            'notes' => 'First session.',
        ])->assertRedirect(route('admin.calendar', ['month' => '2026-11']));

        $appointment = Appointment::query()->firstOrFail();
        $this->assertSame('First session.', $appointment->notes);

        $this->actingAs($admin)->put(route('admin.appointments.update', $appointment), [
            'customer_id' => $customer->id,
            'treatment_slug' => 'deep-tissue-massage',
            'appointment_date' => '2026-11-20',
            'appointment_time' => '11:00',
            'duration_minutes' => 90,
            'status' => 'completed',
            'notes' => 'Treatment completed.',
        ])->assertRedirect(route('admin.calendar', ['month' => '2026-11']));

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'duration_minutes' => 90,
            'status' => 'completed',
        ]);
    }

    public function test_appointment_edit_page_shows_customer_phone_number_immediately(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create(['phone' => '07123 456789']);
        $appointment = Appointment::factory()->for($customer)->create();

        $this->actingAs($admin)
            ->get(route('admin.appointments.edit', $appointment))
            ->assertOk()
            ->assertSee('Telephone')
            ->assertSee('07123 456789')
            ->assertSee('href="tel:07123 456789"', false);
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

    public function test_admin_can_create_customer_while_scheduling_appointment(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_mode' => 'new',
            'customer_name' => '  New Customer  ',
            'customer_phone' => '  07123 456789  ',
            'treatment_slug' => 'deep-tissue-massage',
            'appointment_date' => '2026-11-21',
            'appointment_time' => '14:30',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
            'notes' => 'First appointment.',
        ])->assertRedirect(route('admin.calendar', ['month' => '2026-11']));

        $customer = Customer::query()->where('phone', '07123 456789')->firstOrFail();

        $this->assertSame('New Customer', $customer->name);
        $this->assertNull($customer->email);
        $this->assertSame('07123 456789', $customer->phone);
        $this->assertDatabaseHas('appointments', [
            'customer_id' => $customer->id,
            'starts_at' => '2026-11-21 14:30:00',
            'duration_minutes' => 60,
        ]);
    }

    public function test_new_customer_phone_is_required_when_scheduling_appointment(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_mode' => 'new',
            'customer_name' => 'New Customer',
            'customer_email' => '',
            'customer_phone' => '',
            'appointment_date' => '2026-11-21',
            'appointment_time' => '14:30',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ])->assertSessionHasErrors([
            'customer_phone' => 'Please enter the new customer’s telephone number.',
        ]);

        $this->assertDatabaseCount('customers', 0);
        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_appointment_start_time_must_use_half_hour_intervals(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_id' => $customer->id,
            'starts_at' => '2026-11-20 11:10:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ])->assertSessionHasErrors([
            'starts_at' => 'Please choose a time from the list.',
        ]);

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_appointment_start_time_must_be_within_opening_hours(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_id' => $customer->id,
            'starts_at' => '2026-11-20 19:30:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ])->assertSessionHasErrors([
            'starts_at' => 'Please choose a time from the list.',
        ]);

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_appointment_duration_must_be_sixty_or_ninety_minutes(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_id' => $customer->id,
            'starts_at' => '2026-11-20 11:00:00',
            'duration_minutes' => 45,
            'status' => Appointment::STATUS_SCHEDULED,
        ])->assertSessionHasErrors([
            'duration_minutes' => 'Please choose either 60 or 90 minutes.',
        ]);

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_required_appointment_messages_are_in_english(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_mode' => 'existing',
        ])->assertSessionHasErrors([
            'customer_id' => 'Please choose an existing customer.',
            'starts_at' => 'Please choose a date and time.',
            'duration_minutes' => 'Please choose a duration.',
            'status' => 'Please choose a status.',
        ]);

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_existing_customer_email_cannot_be_created_again_from_appointment_form(): void
    {
        $admin = User::factory()->admin()->create();
        Customer::factory()->create(['email' => 'existing@example.com']);

        $this->actingAs($admin)->post(route('admin.appointments.store'), [
            'customer_mode' => 'new',
            'customer_name' => 'Duplicate Customer',
            'customer_email' => 'EXISTING@EXAMPLE.COM',
            'customer_phone' => '07123 456789',
            'starts_at' => '2026-11-20 11:00:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ])->assertSessionHasErrors([
            'customer_email' => 'A customer with this email address already exists. Choose that customer from the search instead.',
        ]);

        $this->assertDatabaseCount('customers', 1);
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
