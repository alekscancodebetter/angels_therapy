<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmedCustomer;
use App\Mail\BookingRequestReceivedBusiness;
use App\Mail\BookingRequestReceivedCustomer;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingNotificationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_booking_request_is_held_pending_and_queues_customer_and_business_emails(): void
    {
        Mail::fake();
        config()->set('mail.business_recipients', [
            'contact@angeltouchtherapy.co.uk',
            'owner@example.com',
        ]);
        $this->travelTo(CarbonImmutable::parse('2026-10-14 09:00:00', 'Europe/London'));

        $this->post(route('bookings.store'), [
            'starts_at' => '2026-10-16T10:00',
            'treatment_slug' => 'sports-massage',
            'duration_minutes' => 90,
            'name' => 'Jane Visitor',
            'email' => 'jane@example.com',
            'phone' => '07911 123 456',
            'message' => 'Please focus on my shoulders.',
            'terms_accepted' => '1',
        ])->assertRedirect(route('booking-confirmation'))
            ->assertSessionHasNoErrors();

        $appointment = Appointment::query()->with('customer')->firstOrFail();

        $this->assertSame(Appointment::STATUS_PENDING_CONFIRMATION, $appointment->status);
        Mail::assertQueued(
            BookingRequestReceivedCustomer::class,
            fn (BookingRequestReceivedCustomer $mail): bool => $mail->hasTo('jane@example.com')
                && $mail->appointment->is($appointment),
        );
        Mail::assertQueued(
            BookingRequestReceivedBusiness::class,
            fn (BookingRequestReceivedBusiness $mail): bool => $mail->hasTo('contact@angeltouchtherapy.co.uk'),
        );
        Mail::assertQueued(
            BookingRequestReceivedBusiness::class,
            fn (BookingRequestReceivedBusiness $mail): bool => $mail->hasTo('owner@example.com'),
        );
        Mail::assertQueued(BookingRequestReceivedBusiness::class, 2);
    }

    public function test_pending_booking_blocks_another_request_for_the_same_time(): void
    {
        Mail::fake();
        $this->travelTo(CarbonImmutable::parse('2026-10-14 09:00:00', 'Europe/London'));
        Appointment::factory()->create([
            'starts_at' => '2026-10-16 10:00:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_PENDING_CONFIRMATION,
        ]);

        $this->post(route('bookings.store'), [
            'starts_at' => '2026-10-16T10:00',
            'treatment_slug' => 'sports-massage',
            'duration_minutes' => 60,
            'name' => 'Another Visitor',
            'email' => 'another@example.com',
            'phone' => '07911 654 321',
            'terms_accepted' => '1',
        ])->assertSessionHasErrors(['starts_at']);

        $this->assertDatabaseCount('appointments', 1);
        Mail::assertNothingQueued();
    }

    public function test_confirming_pending_booking_queues_customer_confirmation(): void
    {
        Mail::fake();
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create(['email' => 'jane@example.com']);
        $appointment = Appointment::factory()->for($customer)->create([
            'starts_at' => '2026-11-20 11:00:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_PENDING_CONFIRMATION,
        ]);

        $this->actingAs($admin)->put(route('admin.appointments.update', $appointment), [
            'customer_id' => $customer->id,
            'treatment_slug' => $appointment->treatment_slug,
            'appointment_date' => '2026-11-20',
            'appointment_time' => '11:00',
            'duration_minutes' => 60,
            'status' => Appointment::STATUS_SCHEDULED,
            'notes' => '',
        ])->assertRedirect(route('admin.calendar', ['month' => '2026-11']));

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);
        Mail::assertQueued(
            BookingConfirmedCustomer::class,
            fn (BookingConfirmedCustomer $mail): bool => $mail->hasTo('jane@example.com'),
        );
    }

    public function test_booking_business_email_escapes_customer_message(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'Jane Visitor',
            'email' => 'jane@example.com',
        ]);
        $appointment = Appointment::factory()->for($customer)->create([
            'notes' => '<script>alert("booking")</script>',
        ]);

        $html = (new BookingRequestReceivedBusiness($appointment->load('customer')))->render();

        $this->assertStringNotContainsString('<script>alert("booking")</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }
}
