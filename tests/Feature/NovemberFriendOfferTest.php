<?php

namespace Tests\Feature;

use App\Mail\BookingRequestReceivedBusiness;
use App\Mail\BookingRequestReceivedCustomer;
use App\Models\Appointment;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class NovemberFriendOfferTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_homepage_shows_the_offer_and_claim_checkbox_on_the_last_day(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-11-30 23:59:59', 'Europe/London'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Book for a friend. Earn a free massage.')
            ->assertSee('Once your friend pays for their first massage')
            ->assertSee('30 November 2026')
            ->assertSee('name="friend_offer_requested"', false)
            ->assertSee('You qualify for one free 60-minute massage after your friend pays for their first massage.');
    }

    #[TestWith(['2026-10-31 23:59:59'])]
    #[TestWith(['2026-12-01 00:00:00'])]
    public function test_homepage_hides_the_offer_and_claim_checkbox_outside_november(string $dateTime): void
    {
        $this->travelTo(CarbonImmutable::parse($dateTime, 'Europe/London'));

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Book for a friend. Earn a free massage.')
            ->assertDontSee('name="friend_offer_requested"', false);
    }

    public function test_claim_is_saved_and_shown_in_admin_and_booking_emails(): void
    {
        Mail::fake();
        config()->set('mail.business_recipients', ['owner@example.com']);
        $this->travelTo(CarbonImmutable::parse('2026-11-30 23:59:59', 'Europe/London'));

        $this->followingRedirects()->post(route('bookings.store'), $this->bookingDetails() + ['friend_offer_requested' => '1'])
            ->assertOk()
            ->assertSee('your free 60-minute massage once your friend pays for their first massage');

        $this->assertDatabaseCount('appointments', 1);
        $appointment = Appointment::query()->with('customer')->sole();
        $this->assertTrue($appointment->friend_offer_requested);
        $this->assertSame(Appointment::STATUS_PENDING_CONFIRMATION, $appointment->status);

        Mail::assertQueued(BookingRequestReceivedBusiness::class, function (BookingRequestReceivedBusiness $mail): bool {
            $mail->assertSeeInHtml('Verify that the friend has paid for their first massage before arranging the free 60-minute massage');

            return $mail->hasTo('owner@example.com');
        });
        Mail::assertQueued(BookingRequestReceivedCustomer::class, function (BookingRequestReceivedCustomer $mail): bool {
            $mail->assertSeeInHtml('your free 60-minute massage once your friend pays for their first massage');

            return $mail->hasTo('jane@example.com');
        });

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.appointments.edit', $appointment))
            ->assertOk()
            ->assertSee('Verify that the friend has paid for their first massage before arranging the free 60-minute massage');
    }

    public function test_booking_rejects_an_expired_claim(): void
    {
        Mail::fake();
        $this->travelTo(CarbonImmutable::parse('2026-12-01 00:00:00', 'Europe/London'));

        $this->post(route('bookings.store'), $this->bookingDetails() + ['friend_offer_requested' => '1'])
            ->assertRedirect(route('home').'#booking')
            ->assertSessionHasErrors(['friend_offer_requested' => 'The November friend offer is unavailable. You can still submit a regular booking.']);

        $this->assertDatabaseCount('appointments', 0);
        Mail::assertNothingQueued();
    }

    /** @return array{starts_at: string, treatment_slug: string, duration_minutes: int, name: string, email: string, phone: string, terms_accepted: string} */
    private function bookingDetails(): array
    {
        return [
            'starts_at' => '2026-12-02T10:00',
            'treatment_slug' => 'sports-massage',
            'duration_minutes' => 60,
            'name' => 'Jane Visitor',
            'email' => 'jane@example.com',
            'phone' => '07911 123 456',
            'terms_accepted' => '1',
        ];
    }
}
