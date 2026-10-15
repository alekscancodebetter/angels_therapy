<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class BookingCalendarTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_booked_time_is_unavailable_while_other_times_on_same_day_remain_available(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-10-14 09:00:00', 'Europe/London'));
        Appointment::factory()->create([
            'starts_at' => '2026-10-19 10:00:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);
        Appointment::factory()->create([
            'starts_at' => '2026-10-19 09:00:00',
            'duration_minutes' => 60,
            'status' => 'cancelled',
        ]);

        $response = $this->getJson(route('appointments.availability', ['month' => '2026-10']))
            ->assertOk()
            ->assertJsonPath('month', '2026-10');

        $monday = collect($response->json('days'))->firstWhere('date', '2026-10-19');
        $slotAvailability = collect($monday['slots'])
            ->mapWithKeys(fn (array $slot): array => [$slot['value'] => $slot['available']]);

        $this->assertSame(11, $slotAvailability->count());
        $this->assertTrue($slotAvailability['2026-10-19T09:00']);
        $this->assertFalse($slotAvailability['2026-10-19T10:00']);
        $this->assertTrue($slotAvailability['2026-10-19T11:00']);
    }

    public function test_customer_can_book_an_available_time_into_the_admin_calendar(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-10-14 09:00:00', 'Europe/London'));

        $this->post(route('bookings.store'), [
            'starts_at' => '2026-10-16T10:00',
            'treatment_slug' => 'sports-massage',
            'name' => 'Jane Visitor',
            'email' => 'JANE@example.com',
            'phone' => '07911 123 456',
            'message' => 'Please focus on my shoulders.',
        ])->assertRedirect(route('home').'#booking')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('booking_status');

        $customer = Customer::query()->where('email', 'jane@example.com')->firstOrFail();
        $appointment = Appointment::query()->whereBelongsTo($customer)->firstOrFail();

        $this->assertSame('Jane Visitor', $customer->name);
        $this->assertSame('07911 123 456', $customer->phone);
        $this->assertSame('sports-massage', $appointment->treatment_slug);
        $this->assertSame('2026-10-16 10:00', $appointment->starts_at->format('Y-m-d H:i'));
        $this->assertSame(60, $appointment->duration_minutes);
        $this->assertSame(Appointment::STATUS_SCHEDULED, $appointment->status);
        $this->assertSame('Please focus on my shoulders.', $appointment->notes);

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get(route('admin.calendar', ['month' => '2026-10']))
            ->assertOk()
            ->assertSee('Jane Visitor')
            ->assertSee('10:00');
    }

    public function test_booking_rejects_a_time_that_has_just_been_taken(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-10-14 09:00:00', 'Europe/London'));
        Appointment::factory()->create([
            'starts_at' => '2026-10-16 10:00:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $this->from(route('home').'#booking')->post(route('bookings.store'), [
            'starts_at' => '2026-10-16T10:00',
            'treatment_slug' => 'swedish-massage',
            'name' => 'Mia Harris',
            'email' => 'mia@example.com',
            'phone' => '07911 654 321',
            'message' => '',
        ])->assertRedirect(route('home').'#booking')
            ->assertSessionHasErrors(['starts_at']);

        $this->assertDatabaseCount('appointments', 1);
        $this->assertDatabaseMissing('customers', ['email' => 'mia@example.com']);
    }

    public function test_booking_rejects_a_time_outside_the_public_calendar(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-10-14 09:00:00', 'Europe/London'));

        $this->post(route('bookings.store'), [
            'starts_at' => '2026-10-17T10:00',
            'treatment_slug' => 'swedish-massage',
            'name' => 'Mia Harris',
            'email' => 'mia@example.com',
            'phone' => '07911 654 321',
        ])->assertSessionHasErrors(['starts_at']);

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_home_page_shows_contact_details_before_calendar_and_practice_details_after_it(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder([
                'Who is the treatment for?',
                'Available appointments',
                'Everything you need for your visit.',
            ])
            ->assertSee('data-availability-url="'.route('appointments.availability').'"', false)
            ->assertSee('action="'.route('bookings.store').'"', false);
    }

    public function test_availability_rejects_months_outside_the_booking_window(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-10-14 09:00:00', 'Europe/London'));

        $this->getJson(route('appointments.availability', ['month' => '2027-02']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['month']);
    }
}
