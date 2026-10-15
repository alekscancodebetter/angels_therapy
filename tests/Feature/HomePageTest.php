<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_contains_core_practice_information(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Sports Massage')
            ->assertSee('Deep Tissue Massage')
            ->assertSee('ITEC Level 3 Diploma in Sports Massage')
            ->assertSee('07915 910 033')
            ->assertSee('137 Wetherby Road')
            ->assertSee('images/angel-touch-mark.png', false)
            ->assertSee('images/sports-massage.png', false)
            ->assertSee('images/deep-tissue-massage.png', false)
            ->assertSee('images/swedish-massage.png', false)
            ->assertSee('images/full-body-massage.png', false)
            ->assertSee('<a class="nav-booking-link" href="#booking">', false)
            ->assertSee('data-cookie-consent', false)
            ->assertSee('Necessary only')
            ->assertSee('Accept');
    }

    public function test_google_reviews_appear_near_the_top_of_the_page_and_link_to_google(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder([
                'Real stories from Google.',
                'Massage treatments',
            ])
            ->assertSee('data-google-reviews-carousel', false)
            ->assertSee('Ryan Gordon')
            ->assertSee('https://share.google/7oFQqDX4FM6MUO6Ox', false)
            ->assertSee('Natasha Eustance')
            ->assertSee('https://share.google/kvsnKwIddPlcgKr5P', false)
            ->assertSee('audrey henderson')
            ->assertSee('https://share.google/x7tEj7z1JKDz8za8D', false)
            ->assertSee('<span class="google-reviews__position" aria-live="polite"><b data-google-reviews-current>1–3</b> / 12</span>', false)
            ->assertSee('https://search.google.com/local/writereview?placeid=ChIJmwsJacNTeUgRbTtkdSsZo2o', false)
            ->assertSee('Leave a review')
            ->assertSee('images/fht-logo.png', false)
            ->assertSee('Federation of Holistic Therapists logo');
    }

    public function test_an_appointment_request_can_be_stored(): void
    {
        $this->post('/appointment-request', [
            'name' => 'Jane Visitor',
            'email' => 'jane@example.com',
            'message' => 'I would like to ask about an appointment.',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('appointment_requests', [
            'email' => 'jane@example.com',
        ]);
    }

    public function test_appointment_request_requires_valid_fields(): void
    {
        $this->post('/appointment-request', [
            'name' => '',
            'email' => 'not-an-email',
            'message' => '',
        ])->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
